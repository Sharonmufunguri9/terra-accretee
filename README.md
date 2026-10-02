# Terra-accrete Global — Email-enabled prototype

This version adds a lightweight PHP backend.

## Forms

**Investor Access** emails the submitted name, institution, email and investment focus to the configured Terra-accrete mailbox.

**Project Submission** emails project, commodity, jurisdiction, capital requirement, contact details and overview to the configured mailbox.

Both forms also send an automatic confirmation email to the person who submitted the form.

## Fast cPanel deployment

1. Upload the files to `public_html`.
2. Copy `config.example.php` to `config.php`.
3. Edit `config.php`:
   - `recipient_email`: mailbox where enquiries are received.
   - `from_email`: real mailbox on the Terra-accrete domain.
4. Ensure PHP is enabled.
5. Test both forms.

This endpoint uses PHP `mail()`. Delivery depends on the hosting provider's mail configuration. For production, use a real domain mailbox and configure SPF, DKIM and DMARC. If the host requires authenticated SMTP, replace the mail transport with SMTP/PHPMailer.

## Receiving normal email

The website can receive **form enquiries by email**. Normal inbound mail such as `hello@terra-accrete.com` is handled by the domain's email service (cPanel mail, Microsoft 365, Google Workspace, etc.), not by the website frontend.

## Security

Do not put email passwords or SMTP credentials into HTML or JavaScript. Keep mail credentials server-side.
