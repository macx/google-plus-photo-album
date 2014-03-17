'use strict';

module.exports = function (grunt) {

  // Load grunt tasks automatically
  require('load-grunt-tasks')(grunt);

  // Time how long tasks take. Can help when optimizing build times
  require('time-grunt')(grunt);

  // Define the configuration for all the tasks
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    // Project settings
    conf: {
      src: 'src',
      demo: 'examples',
      tests: 'tests'
    },

    php: {
      demo: {
        options: {
          hostname: '127.0.0.1',
          base: '<%= conf.demo %>',
          port: 5000,
          open: true
        }
      }
    },

    sass: {
      build: {
        options: {
          outputStyle: 'compressed',
          sourceComments: 'map'
        },
        files: [{
          expand: true,
          cwd: '<%= conf.src %>',
          src: ['**/*.scss'],
          dest: '<%= conf.demo %>',
          ext: '.css'
        }]
      },
    },

    watch: {
      gruntfile: {
        files: ['Gruntfile.js']
      },
      sass: {
        files: ['<%= conf.src %>/*.scss'],
        tasks: ['sass']
      },
      demo: {
        files: [
          'GooglePlusPhotoAlbum.php',
          '<%= conf.demo %>/index.php',
          '<%= conf.demo %>/styles.css'
        ],
        options: {
          livereload: true
        }
      }
    },

    jshint: {
      options: {
        jshintrc: '.jshintrc',
        reporter: require('jshint-stylish')
      },
      gruntfile: [
        'Gruntfile.js'
      ]
    },
  });

  // Default task.
  grunt.registerTask('default', function () {
    grunt.task.run([
      'jshint',
      'sass'
    ]);

    grunt.log.writeln('Run `grunt serve` to start a php server and open the demo.');
  });

  grunt.registerTask('serve', [
    'default',
    'php:demo',
    'watch'
  ]);
};
