// webpack.config.js
const Encore = require("@symfony/webpack-encore");

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore.setOutputPath("public/build/")
    .setPublicPath("/build")
    .addEntry("app", "./assets/app.js")

    .enableStimulusBridge("./assets/controllers.json") // si tu utilises Stimulus (facultatif)

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .enableSassLoader()
    .enablePostCssLoader()

    .configureBabel((config) => {
        config.presets.push([
            "@babel/preset-env",
            { useBuiltIns: "usage", corejs: 3 },
        ]);
    });

module.exports = Encore.getWebpackConfig();
