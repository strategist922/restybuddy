local confback = ngx.shared.conf
ngx.say(confback:get(ngx.var.arg_k))
