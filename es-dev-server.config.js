const proxy = require("koa-proxies");

module.exports = {
    port: 3000,
    hostname: "192.168.122.1",
    nodeResolve: true,
    watch: true,
    open: true,
    preserveSymlinks: true,
    appIndex: "index.html",
    debug: true,
    middlewares: [
        proxy("/wp-json/", {
            changeOrigin: true,
            target: "https://www.radentscheid-bonn.de/"
        }),
        (context, next) => {
            if (context.url === "/mapbox-gl.css") {
                context.url = "/node_modules/mapbox-gl/dist/mapbox-gl.css";
            }
            return next();
        }
    ],
};
