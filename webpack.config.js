const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require('path');


module.exports = {
	...defaultConfig,
	output: {
		filename: '[name].js',
		path: path.join( __dirname, "admin/gutenberg" )
	}
}