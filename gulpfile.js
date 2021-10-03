const gulp = require("gulp"),
    sass = require("gulp-dart-sass"),
    cleancss = require("gulp-clean-css"),
    rename = require("gulp-rename"),
    autoprefixer = require("gulp-autoprefixer");

const dirs = {
    src: "resources/",
    build: "assets/"
};

let path = {
    src: {
        css: dirs.src + "css/",
        scss: dirs.src + "scss/"
    },
    build: {
        css: dirs.build + "css/",
        scss: dirs.build + "scss/"
    }
};

const files = {
    styles: "**/*.{scss,css}"
};

path = Object.assign({
    watch: {
        styles: path.src.scss + files.styles
    }
}, path);

gulp.task("style", () => {
    return gulp.src(path.src.scss + "*.scss", {allowEmpty: true})
        .pipe(sass({
            outputStyle: "expanded",
            indentWidth: 2
        }))
        .pipe(autoprefixer({
            cascade: false,
            flexbox: false
        }))
        .pipe(gulp.dest(path.src.css))
        .pipe(cleancss({
            level: 2,
            compatibility: "ie8"
        }))
        .pipe(rename({suffix: ".min"}))
        .pipe(gulp.dest(path.build.css))
        .pipe(gulp.dest(path.src.css));
});

gulp.task("css-update", () => {
    return gulp.src([
        path.src.css + "*.css",
        "!" + path.src.css + "*.min.css"
    ])
        .pipe(cleancss({
            level: 2,
            compatibility: "ie8"
        }))
        .pipe(rename({suffix: ".min"}))
        .pipe(gulp.dest(path.build.css))
        .pipe(gulp.dest(path.src.css));
});

gulp.task("compile", gulp.series("style", "css-update"));

gulp.task("default", gulp.parallel("compile", () => {
    gulp.watch(path.watch.styles, gulp.series("style"));
}));
