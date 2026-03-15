import fs from "fs";

const pkg = JSON.parse(fs.readFileSync("package.json", "utf8"));
const version = pkg.version;

const path = "input.css";
let css = fs.readFileSync(path, "utf8");

css = css.replaceAll(version, "__VERSION__");

fs.writeFileSync(path, css);