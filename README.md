Installation:

a) Install Node.js
b) Setup Heroku user account
c) Install the Heroku toolbelt

This gave me issues here on Windows 7 x64. I had to manually add the bin paths for git and ruby to the PATH environment variable.

C:\Program Files (x86)\Git\bin;C:\Program Files (x86)\Heroku\ruby-1.9.2\bin

I also had issues getting Foreman working. To fix this, I have to remove Foreman and install an older version. (I had to give ruby.exe admin rights for this to work.)
> gem uninstall foreman

> gem install foreman -v 0.61

d) Now in the console navigate to the app folder (the one with server.js inside it) and type:
> heroku login

It will respond with:

Enter your Heroku credentials.

Email: xxxx@example.com

Password:

Could not find an existing public key.

Would you like to generate one? [Yn]

Generating new SSH public key.

Uploading ssh public key /Users/xxxx/.ssh/id_rsa.pub


If you didn't add the git bin path to your path environment variable, it will fail when it tries to generate the new key.


e) Now check if the Heroku toolbelt added a remote to git by typing:
> git remote -v

If the 'heroku' remote is the same as the one below, then leave it alone. Otherwise:
If the remote is different type:
> git remote set-url heroku git@heroku.com:node-parse-json.git

Or, if the remote is not there at all, type:
> git remote add heroku git@heroku.com:node-parse-json.git


f) Then to push the app to Heroku, type:
> git push heroku master


g) Now to get the app runnning, type:
> heroku ps:scale web=1

> heroku ps


If you need to restart the app, type:
> heroku restart



Usage:

To test this web service under normal conditions, send your JSON data as a POST request to http://node-parse-json.herokuapp.com/.
To test this using the test script 'client.php' (the test script automatically sends JSON data as a POST request to the app)


a) remote testing:

Navigate to test/client.php in your browser.


b) local testing:

Make sure the app is running by typing:
> foreman start
in the console, or to run without Heroku, type:
> node server.js
Then navigate to http://localhost/APPNAME/test/client.php?local=1 in your browser.



More Information:
https://devcenter.heroku.com/articles/getting-started-with-nodejs