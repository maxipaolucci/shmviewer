var config = {
    build_dir : './app/build',
    
    ang_files : {
        components_js : './app/components/**/*.js',
        components_all : './app/components/**/*.*',
        services_js : './app/js/services/*.js',
        main_js : './app/js/*.js',
        vendor_js : './app/js/libs/*.js',
        src_sass : './app/css/sass/*.scss',
        main_html: './app/*.html',
        views_html: './app/views/*.html',
        component_html: './app/components/**/*.html'
    }
};

config.ang_files.src_js = [config.ang_files.main_js, config.ang_files.services_js, config.ang_files.components_js];
config.ang_files.src_html = [config.ang_files.main_html, config.ang_files.views_html, config.ang_files.component_html]

module.exports = config;