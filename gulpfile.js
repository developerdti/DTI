const{src,dest,watch} = require("gulp");
const sass = require('gulp-sass')(require('sass'));
// const plumber = require('plumber');
function css(done){

    src('src/scss/**/*.scss')
    .pipe(sass())
    .pipe(dest("app/assets/css"));


    done();
}

function dev(done){
    watch('src/scss/**/*.scss',css);

    done();
}

exports.css = css;
exports.dev = dev;