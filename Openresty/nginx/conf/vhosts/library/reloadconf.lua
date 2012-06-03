local confback = ngx.shared.conf
--confback:set("filepath.abc",ngx.var.arg_c);
--ngx.say(confback:get("filepath.abc"));

local function trim (s) 
  return (string.gsub(s, "^%s*(.-)%s*$", "%1")) 
end



--check file then add file line to shared memory
lable = ''
for line in io.lines(ngx.var.arg_c) do
  if line ~= '' then 
    temp = line:match('%[(.+)%]')
    if temp then 
      lable = temp 
    elseif  line ~= '' then
      key,value = line:match('(.*)%s*=%s*(.*)')
      -- ngx.say(trim(lable.."."..key));
      -- set successfule but can't get why?
      confback:set(trim(lable.."."..key),trim(value));
      --ngx.say(confback:get(lable.."."..key));
    end
  end
end

ngx.say(ngx.var.arg_c,"    Reload config successfule");
