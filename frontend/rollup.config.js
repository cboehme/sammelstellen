import nodeResolve from "@rollup/plugin-node-resolve";
import {terser} from "rollup-plugin-terser";
import copy from "rollup-plugin-copy";

export default {
    input: "src/index.js",
    output: {
        name: "embedSammelstellen",
        file: "dist/frontend.js",
        format: "iife",
        sourcemap: true,
        indent: false,
        plugins: [terser()]
    },
    treeshake: true,
    plugins: [
        nodeResolve(),
        copy({
            targets: [
                { src: "node_modules/mapbox-gl/dist/mapbox-gl.css", dest: "dist/" }
            ],
        })
    ]
}
