const path = require( 'path' );
const TerserPlugin = require( 'terser-webpack-plugin' );

module.exports = ( env, argv ) => {
    const isProduction = argv.mode === 'production';

    return [
        {
            devtool: isProduction ? 'source-map' : 'eval-source-map',
            entry: {
                js: './assets/js/settings-modal/index.jsx',
            },
            output: {
                filename: 'scc-settings-modal-react.js',
                path: path.resolve( __dirname, 'dist/backend' ),
            },
            optimization: {
                minimize: isProduction,
                minimizer: [
                    new TerserPlugin( {
                        extractComments: true,
                    } ),
                ],
            },
            module: {
                rules: [
                    {
                        test: /\.jsx$/,
                        exclude: /node_modules/,
                        loader: 'esbuild-loader',
                        options: {
                            loader: 'jsx',
                            target: 'es2018',
                            jsxFactory: 'createElement',
                            jsxFragment: 'Fragment',
                        },
                    },
                ],
            },
            resolve: {
                extensions: [ '.js', '.jsx' ],
            },
        },
    ];
};
