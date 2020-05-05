const proxy = require("koa-proxies");

module.exports = {
    port: 3000,
    nodeResolve: true,
    watch: true,
    open: true,
    preserveSymlinks: true,
    appIndex: "index.html",
    debug: true,
    middlewares: [
        proxy("/wp-json/", {
            target: "http://localhost:8000/",
        }),
    ],
};
