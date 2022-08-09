<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use App\Models\Order;
use App\Models\OrderRecipe;
use App\Models\Recipe;
use App\Utilities\Luigis;
use App\Utilities\Pizza;
use App\Enums\OrderStatusEnum;
use App\Enums\PizzaStatusEnum;
use BadFunctionCallException;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderingTest extends TestCase
{
    use RefreshDatabase;

    /** @var Luigis */
    private $luigis;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->luigis = new Luigis();
    }

    public function testMargherita(): void
    {
        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        $magRecipe = Recipe::where('name', 'Margherita')->first();

        try {
            // 1) Create the order
            $order = Order::create(['status' => OrderStatusEnum::STATUS_PENDING->value]);
            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => $magRecipe->id,
            ]);

            $this->assertEquals(1, count($order->recipes));
            $this->assertEquals($magRecipe->id, $order->recipes->first()->id);
            $this->assertEquals(6.99, $order->getPriceAttribute());

            // 2) Deliver the order
            $pizzas = $this->luigis->deliver($order);

            $this->assertEquals(1, count($pizzas));


            // 3) Verify the order
            /** @var Pizza $pizza */
            $pizza = $pizzas->first();
            $this->assertEquals('Margherita', $pizza->getName());
            $this->assertEquals(PizzaStatusEnum::STATUS_COOKED->value, $pizza->getStatus());

            // 4) Eat the pizza
            $pizza->eatSlice();

            $this->assertEquals(7, $pizza->getSlicesRemaining());
            $this->assertEquals(PizzaStatusEnum::STATUS_PARTLY_EATEN->value, $pizza->getStatus());

            while ($pizza->getSlicesRemaining()) {
                $pizza->eatSlice();
            }
            $this->assertEquals(PizzaStatusEnum::STATUS_ALL_EATEN->value, $pizza->getStatus());


            // 5) Verify can't eat an eaten pizza
            $gotException = 'no exception thrown';
            try {
                $pizza->eatSlice();
            } catch (BadFunctionCallException $e) {
                $gotException = 'exception was thrown';
            }
            $this->assertEquals('exception was thrown', $gotException);

        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }

    // todo create test
    public function testMargheritaAndHawaiian(): void
    {
        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        $magRecipe = Recipe::where('name', 'Margherita')->first();
        $hRecipe = Recipe::where('name', 'Hawaiian')->first();

        try {
            // 1) Create the order
            $order = Order::create(['status' => OrderStatusEnum::STATUS_PENDING->value]);
            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => $magRecipe->id,
            ]);
            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => $hRecipe->id,
            ]);

            $this->assertEquals(2, count($order->recipes));
            $this->assertEquals($magRecipe->id, $order->recipes->first()->id);
            $this->assertEquals(6.99 + 8.99, $order->getPriceAttribute());

            // 2) Deliver the order
            $pizzas = $this->luigis->deliver($order);

            $this->assertEquals(2, count($pizzas));

            // 3) Verify the order
            /** @var Pizza $pizza */
            $pizza = $pizzas->first();
            $this->assertEquals('Margherita', $pizza->getName());
            $this->assertEquals(PizzaStatusEnum::STATUS_COOKED->value, $pizza->getStatus());

            // 4) Eat the pizza
            $pizza->eatSlice();

            $this->assertEquals(7, $pizza->getSlicesRemaining());
            $this->assertEquals(PizzaStatusEnum::STATUS_PARTLY_EATEN->value, $pizza->getStatus());

            while ($pizza->getSlicesRemaining()) {
                $pizza->eatSlice();
            }
            $this->assertEquals(PizzaStatusEnum::STATUS_ALL_EATEN->value, $pizza->getStatus());


            // 5) Verify can't eat an eaten pizza
            $gotException = 'no exception thrown';
            try {
                $pizza->eatSlice();
            } catch (BadFunctionCallException $e) {
                $gotException = 'exception was thrown';
            }
            $this->assertEquals('exception was thrown', $gotException);

        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }

    public function testMargheritaWithAddedIngredient(): void
    {
        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        $magRecipe = Recipe::where('name', 'Margherita')->first();
        $ingredient = $magRecipe->ingredients->first();
        $qty = 4;
        $expectedTotal = ($magRecipe->price) + ($ingredient->price * $qty);

        try {
            // 1) Create the order
            $order = Order::create(['status' => OrderStatusEnum::STATUS_PENDING->value]);
            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => $magRecipe->id,
            ]);
            $magRecipe->addIngredient($order, $ingredient, 4);

            $this->assertEquals(1, count($order->recipes));
            $this->assertEquals($magRecipe->id, $order->recipes->first()->id);
            $this->assertEquals($expectedTotal, $order->getPriceAttribute());

            // 2) Deliver the order
            $pizzas = $this->luigis->deliver($order);

            $this->assertEquals(1, count($pizzas));

            // 3) Verify the order
            /** @var Pizza $pizza */
            $pizza = $pizzas->first();
            $this->assertEquals('Margherita', $pizza->getName());
            $this->assertEquals(PizzaStatusEnum::STATUS_COOKED->value, $pizza->getStatus());

            // 4) Eat the pizza
            $pizza->eatSlice();

            $this->assertEquals(7, $pizza->getSlicesRemaining());
            $this->assertEquals(PizzaStatusEnum::STATUS_PARTLY_EATEN->value, $pizza->getStatus());

            while ($pizza->getSlicesRemaining()) {
                $pizza->eatSlice();
            }
            $this->assertEquals(PizzaStatusEnum::STATUS_ALL_EATEN->value, $pizza->getStatus());


            // 5) Verify can't eat an eaten pizza
            $gotException = 'no exception thrown';
            try {
                $pizza->eatSlice();
            } catch (BadFunctionCallException $e) {
                $gotException = 'exception was thrown';
            }
            $this->assertEquals('exception was thrown', $gotException);

        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }

    public function testMargheritaWithRemovedIngredient(): void
    {
        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        $magRecipe = Recipe::where('name', 'Margherita')->first();
        $ingredient = $magRecipe->ingredients->first();

        try {
            // 1) Create the order
            $order = Order::create(['status' => OrderStatusEnum::STATUS_PENDING->value]);
            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => $magRecipe->id,
            ]);
            $magRecipe->removeIngredient($order, $ingredient);

            $this->assertEquals(1, count($order->recipes));
            $this->assertEquals($magRecipe->id, $order->recipes->first()->id);
            $this->assertEquals(6.99, $order->getPriceAttribute());

            // 2) Deliver the order
            $pizzas = $this->luigis->deliver($order);

            $this->assertEquals(1, count($pizzas));

            // 3) Verify the order
            /** @var Pizza $pizza */
            $pizza = $pizzas->first();
            $this->assertEquals('Margherita', $pizza->getName());
            $this->assertEquals(PizzaStatusEnum::STATUS_COOKED->value, $pizza->getStatus());

            // 4) Eat the pizza
            $pizza->eatSlice();

            $this->assertEquals(7, $pizza->getSlicesRemaining());
            $this->assertEquals(PizzaStatusEnum::STATUS_PARTLY_EATEN->value, $pizza->getStatus());

            while ($pizza->getSlicesRemaining()) {
                $pizza->eatSlice();
            }
            $this->assertEquals(PizzaStatusEnum::STATUS_ALL_EATEN->value, $pizza->getStatus());


            // 5) Verify can't eat an eaten pizza
            $gotException = 'no exception thrown';
            try {
                $pizza->eatSlice();
            } catch (BadFunctionCallException $e) {
                $gotException = 'exception was thrown';
            }
            $this->assertEquals('exception was thrown', $gotException);

        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }
}
