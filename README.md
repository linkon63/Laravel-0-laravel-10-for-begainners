# Project Idea
User can create a new help ticket
Admin and user can reply on help ticket
Admin can reject or resolve the ticket
When admin update on the ticket then user will get one notification via email that ticket status is updated
User can give ticket title and description
User can upload a document like pdf or image

* Database Table Structure : 
# Tickets:
title( string ) {required}
description(text) {required}
status(open {default}, resolved, rejected)
attachment(string) {nullable}
user_id {required} filled by laravel
status_changed_by_id {nullable}

# Replies:
body(text) {required}
user_id {required} filled by laravel
ticket_id {required} filled by laravel