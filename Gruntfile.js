module.exports = function(grunt) {
	var fs = require('fs'),
		PACK = grunt.file.readJSON('package.json'),
		path = require('path'),
		chalk = require('chalk'),
		hash = function (...args) {
			var md5 = require('md5');
			let result = "",
				arr = [];
			if(!args.length){
				let time = (new Date()).getTime();
				arr.push("Not arguments");
				result = md5(time).toString();
			}else{
				let text = "";
				for(let index in args){
					let file = args[index];
					file = path.normalize(path.join(__dirname, file));
					try{
						let buff = fs.readFileSync(file, {
							encoding: "utf8"
						}).toString();
						text += buff;
						arr.push(file);
					}catch(e){
						// Ничего не делаем
						arr.push("Not found");
					}
				}
				result = md5(text).toString();
			}
			arr.push(result);
			grunt.log.oklns([chalk.cyan("Generate hash:") + "\n" + chalk.yellow(arr.join("\n"))]);
			return result;
		};

	require('load-grunt-tasks')(grunt);
	require('time-grunt')(grunt);

	var gc = {
		versions: `${PACK.version}`,
		default: [
			//"less",
			//"autoprefixer",
			//"replace",
			//"cssmin",
			//"copy",
			"pug",
			//"lineending",
			//"compress"
		]
	};

	require('load-grunt-tasks')(grunt);
	require('time-grunt')(grunt);

	grunt.initConfig({
		globalConfig : gc,
		pkg : PACK,
		less: {
			css: {
				options : {
					compress: false,
					ieCompat: false,
					plugins: []
				},
				files : {
					/*'assets/modules/food-module/css/main.css' : [
						'src/main.less'
					]*/
				}
			},
		},
		autoprefixer:{
			options: {
				browsers: [
					"last 4 version"
				],
				cascade: true
			},
			css: {
				files: {
					/*'assets/modules/food-module/css/main.css' : [
						'assets/modules/food-module/css/main.css'
					]*/
				}
			},
		},
		replace: {
			css: {
				options: {
					patterns: [
						{
							match: /\/\*.+?\*\//gs,
							replacement: ''
						},
						{
							match: /\r?\n\s+\r?\n/g,
							replacement: '\n'
						}
					]
				},
				files: [
					/*{
						expand: true,
						flatten : true,
						src: [
							'assets/modules/food-module/css/main.css'
						],
						dest: 'assets/modules/food-module/css/',
						filter: 'isFile'
					},*/
				]
			},
		},
		cssmin: {
			options: {
				mergeIntoShorthands: false,
				roundingPrecision: -1
			},
			minify: {
				files: {
					//'assets/modules/food-module/css/main.min.css' : ['assets/modules/food-module/css/main.css'],
				}
			},
		},
		pug: {
			serv: {
				options: {
					doctype: 'html',
					client: false,
					pretty: '\t',
					separator:  '\n',
					data: function(dest, src) {
						return {}
					}
				},
				files: [
					{
						expand: true,
						cwd: __dirname + '/src/pug/',
						src: [ '*.pug' ],
						dest: __dirname + '/com_food/',
						ext: '.xml'
					},
				]
			},
		},
		lineending: {
			dist: {
				options: {
					eol: 'lf'
				},
				files: [
					/*{
						expand: true,
						cwd: 'com_food',
						src: ['**//*.{css,js,php,json,html}'],
						dest: 'com_food'
					}*/
				]
			}
		},
		compress: {
			main: {
				options: {
					archive: 'com_food.zip'
				},
				files: [
					{
						expand: true,
						cwd: '.',
						src: [
							'',
							'',
						],
						dest: '/'
					},
				],
			},
		},
	});
	grunt.registerTask('default',	gc.default);
};
