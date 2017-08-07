# xero-accounting-php

# TODO
Add badges 

[Xero Accounting API](https://developer.xero.com/) for PHP.

This is a 100% fully unit tested and (mostly) fully featured unofficial PHP client for the Xero Accounting package

Depends on `xero-oauth-php` request handler.

> "Beautiful accounting software"

```bash
composer require darrynten/xero-accounting-php
```

PHP 7.0+

## Basic use

[JSON support](https://devblog.xero.com/json-for-the-accounting-api-974a3b8adfb4) is finally here,
although the current models in the documentation are still in XML.

# TODO

## Features

This is the basic outline of the project and is a work in progress.

Checkboxes have been placed at each section, please check them off
in this readme when submitting a pull request for the features you
have covered.

### Basic ORM-style mapping

Related models are auto-loaded and are all queryable, mutable, and persistable where possible.

I'm sure there will be a recursion issue because of this at some point!

Some examples

```php
$account = new Account($config);

// get
$account->all(); // fetches ALL
$account->get($id); // fetches that ID

// If model supports some query parameters, we can pass it
$company = new Company($config);
$company->all(['includeStatus' => true]);
// Currently get() does not support any query parameters but this might be required in future

// related models
echo $account->category->id;

// dates
echo $account->defaultTaxType->modified->format('Y-m-d');

// assign
$account->name = 'New Name';

// save, delete
$account->save(); // incomplete
$account->category->save(); // saving a child does not save the parent and vice-versa
$account->delete();
```

### Application base

* Guzzle is used for the communications (I think we should replace?)
* The library has 100% test coverage
* The library supports framework-agnostic caching so you don't have to
worry about which framework your package that uses this package is going
to end up in.

The client is not 100% complete and is a work in progress, details below.

## Documentation

Initial focus is only on a handful of of the existing endpoints

Each section must have a short explaination and some example code like on
the API docs page.

Checked off bits are complete.


## Note

This software is under active development and is not production ready

The API calls are mostly _very_ generic, so there is a base model in place that
all other models extend off, which covers the following functionalities:

- GET Model/
- GET Model/{id}
- POST Model/   //Create & update
- PUT Model/    //Create only, throws an error if the model already exists
- DELETE Model/{id}

This means that it's trivial to add new models that only use these calls (or a
combination of any of them) as there is a very simple 'recipe' to constructing
a basic model.

As such we only need to focus on the tricky bits.

# NB the project is evolving quickly

## This might be outdated

### If it is and you fix it please update this document

#### The best place to look is the example model

### Basic model template

```
Name             | Type             | Additional Information
-------------------------------------------------------------------
Name             | string           | None.
type         	 | AccountType	    | Required, 'only' => 'BANK'
Balance          | decimal          | Read Only / System Generated
ReportingGroupId | nullable integer | None.
status           | nullable string  | 'valid' => 'accountStatusCodes'
except           | type		    | 'BANK'
```

We'll be using that for this example (docblocks excluded from example but are
required)

# TODO
- Add example model
- Add example type
- Add example code

Following that template will very quickly create models for the project.

There is an example mock folder to help you get going quickly.

A lot of the heavy testing is handled by the BaseAccountingModelTest class. It makes
testing and getting good defensive coverage quite trivial for most things.

# TODO
Add example test

We aim to have all models tested against mocks provided by Xero's docs.

# NB initial delivery consists of only these models:

TODO - Mark models with an asterix that are pure CRUD models

- [x] Base
- [x] Exception Handling
- [ ] CRUD
- [ ] Save Call
- [ ] Real CRUD Response Mocks
- [ ] Pagination
- [ ] Models
  - [ ] Expense Claims
  - [ ] Users
  - [ ] Contacts
    - [ ] Contact Persons
  - [ ] Receipts
    - [ ] Tracking Categories
    - [ ] Tracking Options
    - [ ] Line items
    - [ ] Attachments
  - [ ] Payments
    - [x] Accounts
    - [ ] Credit Notes ?
    - [ ] Invoices
      - [ ] Items ?
    - [ ] Overpayments ?
    - [ ] Prepayments ?
- [ ] Types	//These are essentially simple enums
  - [x] Account Type
  - [x] Account Class Type
  - [ ] Address types
  - [x] Bank Account Types
  - [x] Bank Transaction Types
  - [x] Credit Note Types
  - [x] Invoice Types
  - [x] Line Amount Types
  - [x] Overpayment Types
  - [ ] Payment Terms
  - [x] Payment Types
  - [x] Phone types
  - [x] Prepayment Types
  - [ ] Receipt Status
  - [ ] System Account Types
  - [ ] Tax Types
  - [ ] User Roles
- [ ] Codes	//These are essentially simple enums
  - [x] Account Status Codes
  - [x] Contact Status Codes
  - [ ] Credit Note Status Codes
  - [ ] Expense Claim Status Codes
  - [ ] Invoice Status Codes
  - [ ] OverPayment Status Codes
  - [ ] Payment Status Codes
  - [ ] Prepayment Status Codes

And any related models not listed, so if ExampleModel has a reference to ExampleCategory
but that is not on the list above it too must get processed

# ==== END OF INITIAL DELIVERY ====

## Deliverables

* 100% Test Coverage
* Full, extensive, verbose, and defensive unit tests
* Mocks if there are none for the model in the `tests/mocks` directory (convention
can be inferred from the existing names in the folders)

### Future Planned Roadmap, as and when needed

Please feel free to open PRs for any of the following :)

#### Models
- [ ] Bank Transactions
- [ ] Bank Transfers
- [ ] Branding Themes
- [ ] Contact Groups
- [ ] Currencies
- [ ] Employees
- [ ] Invoice Reminders
- [ ] Journals
- [ ] Linked Transactions
- [ ] Manual Journals
- [ ] Organisation
- [ ] Purchase Orders
- [ ] Repeating Invoices
- [ ] Reports
- [ ] Tax Rates
- [ ] Tracking

#### Types
- [ ] Bank Transaction Types
- [ ] Organisation Types
- [ ] Version Types
#### Codes

- [ ] Bank Transaction Status Codes
- [ ] Currency Codes
- [ ] Purchase Order Status Codes

### Request Limits

Minute Limit: 60 calls in a rolling 60 second window, 
Daily Limit: 5000 calls in a rolling 24 hour window. 
A maximum of 100 results will be returned for list methods, 
regardless of the parameter sent through.

[Details](https://developer.xero.com/documentation/auth-and-limits/xero-api-limits)

Because of this some of them can
benefit from being cached. All caching should be off by default and only
used if explicity set.

### Details

These run through the `darrynten/any-cache` package, and no extra config
is needed. Please ensure that any features that include caching have it
be optional and initially set to `false` to avoid unexpected behaviour.

### Rate Limiting and Queueing
See [Xero API Limits](https://developer.xero.com/documentation/auth-and-limits/xero-api-limits)

# TODO

## Contributing and Testing

There is currently 100% test coverage in the project, please ensure that
when contributing you update the tests. For more info see CONTRIBUTING.md

We would love help getting decent documentation going, please get in touch
if you have any ideas.

## Additional Documentation

* [Xero API Overview](https://developer.xero.com/documentation/api/api-overview)

## Acknowledgements

* [Fergus Strangways-Dixon](https://github.com/fergusdixon)
* [Igor Sergiichuk](https://github.com/igorsergiichuk)
* [Brian Maiyo](https://github.com/kiproping)
