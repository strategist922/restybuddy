local cjson = require "cjson"
local conf = ngx.shared.conf 
local redis = require "resty.redis"
local r = redis:new()
r:set_timeout(1000) -- 1sec

--get from shared config
--sharding in the network layer
local host = conf:get("redis.master.host");
local port = conf:get("redis.master.port");

local ok,err = r:connect(host,port)
if not ok then
  -- do some error reporting 
 ngx.say("failed to connect:",err)
 return
end
-- load key from shared memory
-- then use function to get the result,then use cjson to response the json 
-- local res, err = red:mget("h1234", "h5678")
-- if res then
--    print("res: ", cjson.encode(res))
--  end

res,err = r:set("redishellofromconf","hello redis from conf")
if not ok then 
  ngx.say("failed to set redis hello:",err)
  --return 
end

--ngx.say("set result:",res)
local res,err = r:mget("redishellofromconf")
if res then
    ngx.say("data:",cjson.encode(res))
    return
end