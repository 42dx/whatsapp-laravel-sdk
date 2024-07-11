# Whatsapp Laravel SDK

This Laravel package's goal is to abstract and simplify the integration with the Facebook's Whatsapp Business API through services, facades and methods, as well properly documenting how to work with it, since it is sometimes hard to find the info you need to correctly implement the API.

Check our package on [Packagist](https://packagist.org/packages/42dx/whatsapp-laravel-sdk).

## Project Standards

[WIP]

## Project Meta Data

[![All Contributors](https://img.shields.io/github/all-contributors/42dx/whatsapp-laravel-sdk?color=ee8449&flat&label=Contributors)](https://github.com/42dx/whatsapp-laravel-sdk/blob/beta/README.md#contributors)

## Project Status

[WIP]

## Table of Contents

- [Composer Scripts](#composer-scripts)
- [Run and Test](#run-and-test)
- [Configure Whatsapp Webhook](configure-whatsapp-webhook)
- [Tooling](#tooling)
  - [Comitzen](#comitizen)
  - [Sample App](#sample-app)
  - [Serveo](#serveo)
- [Contributors](#contributors)
- [Changelog](#changelog)
- [Roadmap](#roadmap)

## Composer Scripts

We tried to automate as much boring tasks and CLI commands as we could on short `composer` scripts. Below you will find a general description of what each one does:

- `composer commit`: Runs the [`comitzen` binary](https://github.com/lintingzhen/commitizen-go) which opens an interactive CLI to keep commits in a standard that our pipeline can use to generate the package changelog automatically.
- `composer coverage`: Run the tests and generates code coverage. If you are contributing, please make sure you do not lower the current coverage. ;)
- `composer start`: Serves the sample application (through `php artisan serve`) with the package already loaded. It will run the Sample App on port `8000`.
- `composer serve`:  Connects to Serveo service and expose your local environment to the web. It assumes that the local app is running on port `8000`.
- `composer test`: Run the tests (do not generate coverage report)

## Run and Test

We included a sample fresh Laravel app to help those who want to contribute to the package. To start it just go through the following steps:

1. Clone this repository and `cd` into the sample app folder (`<repository-folder>/samples/laravel-11`).
2. Run `composer setup` from the repository root. It will `cd` on sample app's folder, install the dependencies and `cd` back to the root folder.
3. Run `composer start` from the repository root. It will run the sample app through `artisan serve` command.
4. With the local server running, from another terminal run `composer serve` from the repository. It will connect to [serveo](https://serveo.net) service and expose your local application to the web.

## Configure Whatsapp Webhook

[WIP]

## Tooling

There are a few tools we provide alongside with the source code to ease a little bit the burden of following a bunch of patterns and standards we set up, as well as automate some boring processes.

### Comitizen

This CLI helps with writting commit messages in a meaningful and standardized way, so that our automation process can use them to properly write our software changelog.

If you like the tool, [kudo the devs](https://github.com/lintingzhen/commitizen-go) ;)

#### How to use Comitzen

Just run `composer commit` from the repository's root folder instead of the traditional `git commit` and follow the CLI interactive steps :)

### Sample App

For those who want to test and/or contribute to this package, we included a sample fresh laravel application with the package already locally loaded, so you can emulate a real Laravel application that uses the package :)

#### How to run the Sample App

Just run `composer serve` from the repository's root folder. The script will `cd` into the sample folder and run the Laravel artisan command `php artisan serve` from there.

### Serveo

This service allows routing external requests to your local environment. You can use it to test your Watsapp webooks locally while developing.

If you like the tool, [kudo the devs](https://serveo.net/) ;)

#### How to use Serveo

Just run `composer connect` from the repository's root folder. You will need to have our sample application running so you can receive webhook requests locally.

## Contributors

Kudos to all our dear contributors. Without them, nothing would have been possible :heart:

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tbody>
    <tr>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/rafapaulin"><img src="https://avatars.githubusercontent.com/u/13452406?v=4?s=60" width="60px;" alt="Rafael Eduardo Paulin"/><br /><sub><b>Rafael Eduardo Paulin</b></sub></a><br /><a href="https://github.com/42dx/whatsapp-laravel-sdk/commits?author=rafapaulin" title="Code">💻</a> <a href="#design-rafapaulin" title="Design">🎨</a> <a href="https://github.com/42dx/whatsapp-laravel-sdk/commits?author=rafapaulin" title="Documentation">📖</a> <a href="#ideas-rafapaulin" title="Ideas, Planning, & Feedback">🤔</a> <a href="#infra-rafapaulin" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a> <a href="#maintenance-rafapaulin" title="Maintenance">🚧</a> <a href="#projectManagement-rafapaulin" title="Project Management">📆</a> <a href="https://github.com/42dx/whatsapp-laravel-sdk/pulls?q=is%3Apr+reviewed-by%3Arafapaulin" title="Reviewed Pull Requests">👀</a> <a href="https://github.com/42dx/whatsapp-laravel-sdk/commits?author=rafapaulin" title="Tests">⚠️</a> <a href="#tool-rafapaulin" title="Tools">🔧</a> <a href="#tutorial-rafapaulin" title="Tutorials">✅</a></td>
    </tr>
  </tbody>
</table>
<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

Would you like to see your profile here? Take a look on our [Code of Conduct](https://github.com/42dx/.github/blob/main/CODE_OF_CONDUCT.md) and our [Contributing](https://github.com/42dx/.github/blob/main/CONTRIBUTING.md) docs, and start coding! We would be thrilled to review a PR of yours! :100:

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

## Changelog

All changes made to this package since the start of development can be found either on our [release list](https://github.com/42dx/whatsapp-laravel-sdk/releases) or on the [changelog](CHANGELOG.md).

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>

## Roadmap

Any planned enhancement to the package will be described and tracked in our [project page](https://github.com/orgs/42dx/projects/3)

<p align="right"><em><a href="#table-of-contents">back to top</a></em></p>
