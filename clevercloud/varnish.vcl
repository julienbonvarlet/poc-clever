vcl 4.0;
import std;

backend default {
   .host = "127.0.0.1";
   .port = "8081";
}

sub vcl_recv {
  if (req.method == "PURGE") {
          if (req.http.X-Purge-Auth == "${VARNISH_PURGE_AUTH}") {
               return (purge);
         } else {
            return (synth(405, "Not allowed"));
        }
   }

  if (req.method == "GET" &&
  (
      req.url ~ "/api/front/reference/(.*)" ||
      req.url ~ "/api/front/product/search/(.*)" ||
      req.url ~ "/api/v2/admin/retail/brands/(.*)/articles/filters"
  )
     ) {
      return (hash);
  }

  return (pass);
}

sub vcl_backend_response {
    set beresp.ttl = 3600s;
    if (beresp.ttl > 0s) {
        set beresp.http.x-obj-ttl = beresp.ttl;
    }
    return (deliver);
}

sub vcl_deliver {
  if (obj.hits > 0) {
    set resp.http.X-Varnish-Cache = "HIT";
    set resp.http.X-Varnish       = "HIT";
  } else {
    set resp.http.X-Varnish-Cache = "MISS";
    set resp.http.X-Varnish       = "MISS";
  }
  if (resp.http.x-obj-ttl) {
    set resp.http.Expires = "" + (now + std.duration(resp.http.x-obj-ttl, 3600s));
    unset resp.http.x-obj-ttl;
  }
  if (req.url ~ "/api/front/reference/(.*)" ||
      req.url ~ "/api/front/product/search/(.*)" ||
      req.url ~ "/api/v2/admin/retail/brands/(.*)/articles/filters"
      ) {
      set resp.http.Access-Control-Allow-Origin = "*";
  }
  unset resp.http.X-Powered-By;
  unset resp.http.Server;
  unset resp.http.Via;
  unset resp.http.X-Varnish;
  return (deliver);
}

sub vcl_hit {
  if (req.method == "PURGE") {
    return (synth(200, "OK"));
  }
}

sub vcl_miss {
  if (req.method == "PURGE") {
    return (synth(404, "Not cached"));
  }
}
