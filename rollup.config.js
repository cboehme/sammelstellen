import nodeResolve from "@rollup/plugin-node-resolve";
import {terser} from "rollup-plugin-terser";

export default {
    input: "src/index.js",
    output: {
        file: "output/bundle.js",
        format: "iife",
        plugins: [terser()]
    },
    plugins: [nodeResolve()]
}
