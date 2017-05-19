module.exports = function( grunt ) {

	'use strict';
	var banner = '/**\n * <%= pkg.homepage %>\n * Copyright (c) <%= grunt.template.today("yyyy") %>\n * This file is generated automatically. Do not edit.\n */\n';
	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

    // Setting folder templates.
    dirs: {
      css: 'assets/css',
      images: 'assets/images',
      js: 'assets/js'
    },

		addtextdomain: {
			options: {
				textdomain: 'wc-order-search-admin',
			},
			target: {
				files: {
					src: [ '*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**', '!vendor/**', '!build/**' ]
				}
			}
		},

		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				},
        options: {
          screenshot_url: "https://ps.w.org/wc-order-search-admin/assets/{screenshot}.png"
        }
			},
		},

    // Compile all .scss files.
    sass: {
      compile: {
        options: {
          sourcemap: 'none',
          loadPath: require( 'node-bourbon' ).includePaths
        },
        files: [{
          expand: true,
          cwd: '<%= dirs.css %>/',
          src: ['*.scss'],
          dest: '<%= dirs.css %>/',
          ext: '.css'
        }]
      }
    },

    // Minify all .css files.
    cssmin: {
      minify: {
        expand: true,
        cwd: '<%= dirs.css %>/',
        src: ['*.css'],
        dest: '<%= dirs.css %>/',
        ext: '.css'
      }
    },

    // Minify .js files.
    uglify: {
      options: {
        // Preserve comments that start with a bang.
        preserveComments: /^!/
      },
      frontend: {
        files: [{
          expand: true,
          cwd: '<%= dirs.js %>/',
          src: [
            '*.js',
            '!*.min.js'
          ],
          dest: '<%= dirs.js %>/',
          ext: '.min.js'
        }]
      }
    },

    // Watch changes for assets.
    watch: {
      css: {
        files: ['<%= dirs.css %>/*.scss'],
        tasks: ['sass', 'cssmin']
      },
      js: {
        files: [
          '<%= dirs.js %>/*js',
          '!<%= dirs.js %>/*.min.js'
        ],
        tasks: ['uglify']
      }
    },

    // Autoprefixer.
    postcss: {
      options: {
        processors: [
          require( 'autoprefixer' )({
            browsers: [
              '> 0.1%',
              'ie 8',
              'ie 9'
            ]
          })
        ]
      },
      dist: {
        src: [
          '<%= dirs.css %>/*.css'
        ]
      }
    },

		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					mainFile: 'wc-order-search-admin.php',
					potFilename: 'wc-order-search-admin.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},
	} );

	grunt.loadNpmTasks( 'grunt-wp-i18n' );
  grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  grunt.loadNpmTasks( 'grunt-contrib-watch' );
  grunt.loadNpmTasks( 'grunt-postcss' );
  grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
  grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
  grunt.loadNpmTasks( 'grunt-contrib-sass' );

  // Register tasks
  grunt.registerTask( 'default', [
    'uglify',
    'css',
    'makepot',
    'readme'
  ]);

  grunt.registerTask( 'css', [
    'sass',
    'postcss',
    'cssmin'
  ]);

	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );

	grunt.util.linefeed = '\n';

};
