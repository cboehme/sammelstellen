import nodeResolve from "@rollup/plugin-node-resolve";
import {terser} from "rollup-plugin-terser";
import copy from "rollup-plugin-copy";

export default {
    input: "src/index.js",
    output: {
        file: "../wordpress-plugin/_inc/frontend.js",
        format: "iife",
        plugins: [terser()]
    },
    plugins: [
        nodeResolve(),
        copy({
            targets: [
                { src: "node_modules/mapbox-gl/dist/mapbox-gl.css", dest: "wordpress-plugin/_inc/" }
            ],
        })
    ]
}
