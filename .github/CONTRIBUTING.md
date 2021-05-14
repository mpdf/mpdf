Contributing
============

Issue tracker
-------------

The Issue tracker serves mainly as a place to report bugs and request new features.
Please do not abuse it as a general questions or troubleshooting location.

For these questions you can use the
[mpdf tag](https://stackoverflow.com/questions/tagged/mpdf) at [Stack Overflow](https://stackoverflow.com/).
Make sure you also comply to StackOverflow question guidelines.

* Bug reports **MUST** contain a small example in php/html that reproduces the bug
* The code example **MUST** be reproducible by copy&paste assuming composer dependencies are installed. That means:
    * No calling unrelated funcions
    * An actual final HTML code has to be present, pasting a template file is not enough
    * If the bug considers import or fonts, example source PDF/TTF/etc files have to be included
* Please report one feature or one bug per issue
* Failing to provide necessary information or not using the issue template will cause the issue to be closed until required information is provided.

Pull requests
-------------

Pull requests should be always based on the default [development](https://github.com/mpdf/mpdf/tree/development)
branch except for backports to older versions.

Guidelines:

* Use an aptly named feature branch for the Pull request.
* Only files and lines affecting the scope of the Pull request must be affected.
* Make small, *atomic* commits that keep the smallest possible related code changes together.
* Code must be accompanied by a unit test testing expected behaviour whenever possible.
* To be incorporated, the PR should contain a change in the CHANGELOG.md file describing itself

When updating a PR, do not create a new one, just `git push --force` to your former feature branch, the PR will
update itself.
