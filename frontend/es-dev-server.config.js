module.exports = {
    port: 3000,
    hostname: "localhost",
    nodeResolve: true,
    watch: true,
    open: true,
    preserveSymlinks: true,
    appIndex: "index.html",
    middlewares: [
        (context, next) => {
            if (context.url === "/mapbox-gl.css") {
                context.url = "node_modules/mapbox-gl/dist/mapbox-gl.css";
            }
            return next();
        }
    ],
};
