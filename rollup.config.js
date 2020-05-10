import nodeResolve from "@rollup/plugin-node-resolve";
import {terser} from "rollup-plugin-terser";
import gzipPlugin from "rollup-plugin-gzip";

export default {
    input: "index.js",
    output: {
        file: "bundle.js",
        format: "iife",
        plugins: [terser(), gzipPlugin()]
    },
    plugins: [nodeResolve()]
}
