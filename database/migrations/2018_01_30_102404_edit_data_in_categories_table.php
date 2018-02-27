<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class EditDataInCategoriesTable extends Migration
{
    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    private $db;

    /**
     * Run the migrations.
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function up()
    {
        $this->db = app('db');

        $connection = $this->db->connection();

        if ($connection->table('categories')->where('name', 'Attractions & Leisure')->exists()) {
            $connection->table('categories')->where('name',
                'Attractions & Leisure')->update(['name' => 'Accommodation & Leisure']);
        }

        if ($connection->table('categories')->where('name', 'Jewelery salons')->exists()) {
            $connection->table('categories')->where('name', 'Jewelery salons')->update(['name' => 'Jewellery salons']);
        }

        $accommodationAndLeisure = $connection->table('categories')->where('name',
            'Accommodation & Leisure')->first();

        if (isset($accommodationAndLeisure->id)) {
            $newRetailTypes =
                [
                    $this->generateCategory('Hotels', $accommodationAndLeisure->id),
                    $this->generateCategory('Hostels and BnB', $accommodationAndLeisure->id),
                    $this->generateCategory('Travel Agency', $accommodationAndLeisure->id),
                ];

            foreach ($newRetailTypes as $retailType) {
                if (!$connection->table('categories')->where('name', $retailType['name'])->exists()) {
                    $connection->table('categories')->insert($retailType);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    /**
     * @param string      $name
     * @param string|null $parentId
     *
     * @return array
     * @throws Exception
     */
    private function generateCategory(string $name, string $parentId = null): array
    {
        $categoryArray = [
            'id'         => Webpatser\Uuid\Uuid::generate(4)->__toString(),
            'name'       => $name,
            'created_at' => Carbon::now()->__toString(),
            'updated_at' => Carbon::now()->__toString()
        ];

        if (null !== $parentId) {
            $categoryArray['parent_id'] = $parentId;
        }

        return $categoryArray;
    }
}
