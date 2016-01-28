var config = {
    build_dir : './app/build',
    
    ang_files : {
        components_js : './app/components/**/*.js',
        components_all : './app/components/**/*.*',
        services_js : './app/js/services/*.js',
        main_js : './app/js/*.js',
        vendor_js : './app/js/libs/*.js',
        src_sass : './app/css/sass/*.scss'
    }
};

config.ang_files.src_js = [config.ang_files.main_js, config.ang_files.services_js, config.ang_files.components_js];


module.exports = config;