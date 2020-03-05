local httpc = http.new()
local resp, err = httpc:request_uri("https://videos01.mundi-tv.tk/plugin/Live/on_publish.php", {
        method = "GET",
        --path = "/Stream/luaHttpRequest?"..args_string,
        path = "/stream/privatePushCallbackUrl?"..args_string,
        headers = {
            ["User-Agent"] = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36"
        },
        ssl_verify = false   -- must
})

if not resp then
        LOG(ERR, "resty.http API request error :", err)
        return
end

if resp.status ~= ngx.HTTP_OK then
        LOG(ERR, "request error, status :", resp.status)
        return
end

if resp.status == ngx.HTTP_FORBIDDEN then
        LOG(ERR, "request error, status :", resp.status)
        return
end

if tostring(resp.body) ~= "200" then
        LOG(ERR,"Https APi is Fail return-value = "..resp.body)
        return
end
httpc:close()
return 200
