# Word-Translation-Web-App
Web application that translates user-supplied words, provided a dictionary exists in the database. 

The application features a registration page, in which account information is encrypted and stored using salted hashes. Once logged in, users may upload text files whose contents consist of word translations. Input data is sanitized to prevent SQL injections and XSS, and session security is ensured to prevent session hijacking/fixation.
