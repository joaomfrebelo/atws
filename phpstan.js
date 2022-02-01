const {exec} = require("child_process");
const path = require("path");
const chokidar = require("chokidar");

const srcdir = "./src/";
const testdir = "./test/";

const wconf = {
    ignoreInitial: true,
    delay: 200,
    queue: true
};

let lastTime = 0;
let lastPath = "";

chokidar.watch([srcdir + "**/*.php", testdir + "**/*.php"], wconf)
	.on("change", (phpfilepath) => {

	    let now = new Date();
	    let dif = now.getTime() - lastTime;
	    let sameFile = lastPath === phpfilepath;
	    lastTime = now.getTime();
	    lastPath = phpfilepath;
	    if (dif < 9000 && sameFile) {
		return;
	    }

	    const stanpath = path.join(__dirname, "vendor", "bin", "phpstan");
	    const comm = `${stanpath} analyse ${phpfilepath}`;

	    console.log(`Checking file ${phpfilepath} - ${now.toISOString()}`);

	    exec(comm, {maxBuffer: 1024 * 1024 * 1024}, (error, stdout, stderr) => {
		if (stdout === "" || stdout === null) {
		    console.log(stderr);
		} else {
		    console.log(stdout);
		}
	    });
	});
