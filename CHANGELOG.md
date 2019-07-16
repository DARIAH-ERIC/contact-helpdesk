# Changelog
All notable changes to the Contact Helpdesk will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased
### Added
- Uninstall deletes the DB tables of the plugin
- First public version of the plugin
- If OTRS provides an error, mail the issue to the main OTRS user

### Changed
- Re-hardcoded the OTRS URL because of a new OTRS version that did not accept this dynamically
