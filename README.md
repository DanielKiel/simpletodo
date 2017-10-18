## actual implementation

- An user can write a list. A list is grouped by a token and can have n list entries. Each list entry must have a title, can have
a description and can have some extra data.
- When a list entry is updated it will be versioned - the previous version will be saved as history entry before so we can make some diffs
- Access to a list: you have access when you have created or updated a list entry or when the token is shared to the user - so lists are shareable to other users

## actual development todos

- make it multi tenant
