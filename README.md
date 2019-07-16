# Contact Helpdesk

_Developed by DARIAH for [its website's contact form](https://www.dariah.eu/contact/) (Still pending release)_

_Development is based on the work of [CLARIN-D](https://www.clarin-d.net/) on their own [formular](https://github.com/hzsk/clarind-helpdesk)_

This plugin is used in order to connect a WordPress website to an OTRS server and create issues easily by users
unfamiliar with OTRS.

---

# Install (manually)
1. Upload directory `contact-helpdesk` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to settings / Contact Helpdesk settings in order to set some mandatory options

## Options
- reCaptcha site key (use https://www.google.com/recaptcha/admin)
- reCaptcha secret key (as above)
- Ticketing User (the username of the user creating the ticket on the OTRS system)
- Ticketing User Password (its password)
- Default OTRS Ticket Owner ID (Who would be the owner by default, please use the ID of such user, the ID can be 
found on your OTRS instance)
- Default OTRS Ticket Responsible ID (Who would be responsible by default, please use the ID of such user, the ID 
can be found on your OTRS instance)

# How does it work?
Once the options are created, you can add a shortcode into one of your page (a contact page for example) by adding:
`[contact-helpdesk]`

This plugin will automatically create a form based on the options set in the settings page, and you are good to go.
