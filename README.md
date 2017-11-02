## actual implementation

- An user can write a list. A list is grouped by a token and can have n list entries. Each list entry must have a title, can have
a description and can have some extra data.
- A list can be an ordered list, therefor the field weight can be set - sort is a global scope
- When a list entry is updated it will be versioned - the previous version will be saved as history entry before so we can make some diffs
- Access to a list: you have access when you have created or updated a list entry or when the token is shared to the user - so lists are shareable to other users
- list points are commentable - a comment will have a position field, here the frontend have to save the position where comment belongs to, like title:15 means at the title sign 15 ... so we can place markers or something else to show where comment was saved to
- following a list is possible - when following the user will get notification dependend on the profile settings
- user can have settings - this is like a fast way fir a related profile, it is a json attribute, also a method is available where to get settings and specifying a default value when no setting is configured till yet 

## actual development todos

next:

- make file upload available
- make comment replies available

later:

- make it multi tenant - basic logic is integrated, policies and stuff have to be handled later
- make it featurable (a tenant can have enabled a feature like: calendar where the list can be placed with i calendar, stateable where the list entries can have states like planned, opened, etc)


