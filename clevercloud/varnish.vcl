vcl 4.0;

import std;

backend default {
  .host = "127.0.0.1";
  .port = "8081";
}

sub vcl_deliver {
  if (obj.hits > 0) {
    set resp.http.X-Varnish-Status = "HIT";
  } else {
    set resp.http.X-Varnish-Status = "MISS";
  }
}

sub vcl_recv {
  if (req.restarts > 0) {
    set req.hash_always_miss = true;
  }

  # Remove the "Forwarded" HTTP header if exists (security)
  unset req.http.forwarded;

  if (
    !req.url ~ "/api/v3/(.*)" ||
    !req.url ~ "/api/front/reference/(.*)" ||
    !req.url ~ "/api/front/product/search/(.*)" ||
    !req.url ~ "/api/v2/admin/retail/brands/(.*)/articles/filters"
  ) {
    return (pass);
  }

  # To allow API Platform to ban by cache tags
  if (req.method == "BAN") {
    if (req.http.X-Purge-Auth != "${VARNISH_PURGE_SECRET}") {
        return (synth(405, "Not allowed"));
    }

    if (req.http.ApiPlatform-Ban-Regex) {
      ban("obj.http.Cache-Tags ~ " + req.http.ApiPlatform-Ban-Regex);

      return (synth(200, "Ban added"));
    }

    return (synth(400, "ApiPlatform-Ban-Regex HTTP header must be set."));
  }

  # For health checks
  if (req.method == "GET" && req.url == "/healthz") {
    return (synth(200, "OK"));
  }
}

sub vcl_hit {
  if (obj.ttl >= 0s) {
    # A pure unadulterated hit, deliver it
    return (deliver);
  }

  if (std.healthy(req.backend_hint)) {
    # The backend is healthy
    # Fetch the object from the backend
    return (restart);
  }

  # No fresh object and the backend is not healthy
  if (obj.ttl + obj.grace > 0s) {
    # Deliver graced object
    # Automatically triggers a background fetch
    return (deliver);
  }

  # No valid object to deliver
  # No healthy backend to handle request
  # Return error
  return (synth(503, "API is down"));
}

sub vcl_deliver {
  # Don't send cache tags related headers to the client
  unset resp.http.url;
  # Comment the following line to send the "Cache-Tags" header to the client (e.g. to use CloudFlare cache tags)
  unset resp.http.Cache-Tags;
}

sub vcl_backend_response {
  # Ban lurker friendly header
  set beresp.http.url = bereq.url;

  # Add a grace in case the backend is down
  set beresp.grace = 1h;
}
