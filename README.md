Support
=======

A base package to work with all Jaffle components
It also provides some helpful commands for developing or to setup frontend related stuff for a new application.

###Commands

####Developer Helper Commands

The following commands are smart enough to detect if we need to put the files in the workbench or in the vendor dir.
It will place the file at the same directory as where the support package is located.

So if you run the command in a project where this package is actually a workbench, the class created will be created in the workbench directory.
It's the other way around if the package resides in the vendor dir.

#####Actual commands

All these commands need a namespace argument in dot notation:

`artisan command namespace.in.dot.notation`

- `dev:command`: Use this command to create a new command class.
- `dev:service`: Use this to create a service provider.

**Heads up:** It might be that the namespace you're need, is not working as expected if it contains dashes or underscores.

####New Project Commands

These commands should speed up the process to start up a new project.
Be careful when running these, as they might override anything you've already implemented.

#####Actual Commands

- `frontend:grunt-install`:

This command install all necessary backend dependencies to run grunt, this should normally only be ran once per project.

- `frontend:grunt-deploy`:

This command generates a basic Gruntfile.js that should suffice for a reasonably simple project.

- `frontend:templates`:

Generates the necessary template files for layout structuring. It will setup a frontend, a backend and a email template.


###The Actual Support Package
----

Here you'll find the actual stuff that's been implemented when we talk about code.

#####Eloquent Translations

The eloquent model got extended so we can make use of automatic translation of certain database fields.

- The model allows us to set the correct database column based on the current locale
- The model allows us to read the correct database column based on the current locale
- The model allows us to read a specific database column based on a locale argument

To make this work, you need to have a `locales` entry in the config/app.php file.

**possible todo**
We might need another way which allows us to set a translatable field based on a given locale.

######Implementation

```php
/**
 * You might wanne consider overriding the Eloquent alias to this model
 * Things should be backwards compatible if i'm not mistaking.
 */
class Example extends Jaffle\Support\Database\Eloquent\Model
{

    /**
     * this array holds all columns for the specific model that should be translatable
     *
     * the supported languages depend on the locales specified in the config files by app.locales field
     */
    protected static $translations = array
    (
        'title'
    );

    //that's all you need, as simple as that :-)

}
```

#####Observers

We've implemented a base Observer class which allows for automated tracking of certain fields.
These fields allow us to track when and by who certain fields were last changed.

**possible todo**
you might want to consider to make this a repo on its own, and log all the stuff in a seperate table.
Why?
now we're missing an actual history of the edits.
also, we're adding way to much extra columns to our tables with info we might only need once in a while.

######Implementation

```php

/**
 * Do not forget to actually observe your model!
 */
class Observer extends Jaffle\Support\Database\Eloquent\Observer
{
    public function updating($model)
    {
        //this will track both the date and the user that performed the change
        $this->track('title');
        //this will track only the date
        $this->trackAt('title')
        //this will only track the user whom performed the change
        $this->trackBy('title')
    }
}

```

#####Migrations

We added/extended the base Blueprint to allow us to track user info on the timestamps tracking provided by laravel out of the box.
But you can now also use a shortcut to add extra columns that you wanne track.

For now, usertracking is on by default.

######Implementation

```php

//make sure to add these! as you need to inject the correct blueprint!
use Jaffle\Support\Database\Schema\Blueprint;
use Jaffle\Support\Database\Migrations\Migration;


class ExampleMigration extends Blueprint
{

	public function up()
	{
		$this->schema->create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->boolean('activated')->default(0);
            $table->track('activated');
            $table->timestamps();
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$this->schema->drop('users', function(Blueprint $table)
        {
            //the method to call to drop tracking a certain field
            $table->untrack('activated');

            //these methods are the same as out of the box, but will now also drop our additional columns
            $table->dropTimestamps();
            $table->dropSoftDeletes();
        });
	}

}
```

---


#####Form validator

This should speed up the process of validating a form

######Implementation

```php

//create a form validator class
class ExampleForm extends Jaffle\Support\Validator\FormValidator
{
    protected static $rules = array(
        'firstname' => 'required',
        'lastname' => 'required',
    );

}

/**
 * and you're done for most situations, if you need some sort of model simply inject it into the constructor
 * example: you might wanne check uniqueness of a field in the database, then you'll want to inject the actual model
 */

//next step is to validate the form in the controller simply by calling the validate method

class ExampleController extends BaseController
{
    public function __construct(ExampleForm $form)
    {
        $this->form = $form;
    }

    public function postMethod()
    {
        //this method call is actually enough, as our support package will catch the form exception and redirect back with all the errors
        $this->form->validate(Input::all()))
    }
}

```



###What Do We Want More?

we need to upgrade our deploy script to distinguish between
 - a single tenant application ,
 - a multi tenant application (currently not supported yet)

This needs some extra thought, as this is the most difficult part of our actual goal.
we want to solve our never-ending problem of ... "right how do i structure this"..

make use of config arrays as described on Github issues. (or use another way if you believe it better fits the main idea.)

For multi tenant applications, we need to make sure that only the correct tenant files are updated.
