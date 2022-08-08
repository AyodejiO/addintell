# Let's pretend PHP can bake pizza!

We are looking for clean, understandable and extensible code in this test.
Each new functionality needs to have tests.
Database changes must have migrations and/or seeds.

You will need at least PHP 7.1 & Laravel 5.7.

- Start by installing Laravel and adding the three folders in this package into it.
- Then run the DB migrations & seeds:

```sh
composer dump-autoload
php artisan migrate --seed
```

The archive provides you with:

- Models (`app/Models/*`) - Laravel Eloquent Models
- Utilities (`app/Utilities/*`) - Classes that hold Business Logic
- Seeds (`database/seeds/*`) - Classes that fill the database with pre-configured data
- Feature Test (`tests/Feature/OrderingTest`) - PHPUnit test for the pizza ordering flow

This test is backend-only, so make sure the tests pass.

1) Refactor existing code and tests to make them follow best practices, i.e. eliminate hardcoded values where possible, ensure SOLID is being followed etc. You can keep the existing test file for verification purposes.

2) The owner has bought an electric oven. Implement the `App/Utilities/Oven` interface:
    - It takes 10 minutes to heat up (just echo "10 minutes to heat up")
    - It requires 5 min per pizza + 1 min per ingredient type to bake a pizza (echo "n minutes to bake pizza")

3) People want to add or remove ingredients from pizzas in their order
    - Removing an ingredient from an existing recipe does not change the base price
    - Each new ingredient costs money
    - Ingredients can have different prices (you can make these up)
    - Example: Mozzarella costs 50p, and person adds two extra orders of Mozzarella. Pizza price increases by £1

4) People want to make custom pizzas
    - Implement the Recipe to allow for this
    - Same ingredient cost rules apply here
