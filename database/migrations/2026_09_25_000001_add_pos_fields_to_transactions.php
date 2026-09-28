<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'code')) {
                $table->string('code')->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->unique()->after('code');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'transaction_number')) {
                $table->string('transaction_number')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('transactions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('transaction_number')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('transactions', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('transactions', 'customer_type')) {
                $table->string('customer_type')->default('Umum')->after('customer_id');
            }
            if (! Schema::hasColumn('transactions', 'member_number')) {
                $table->string('member_number')->nullable()->after('customer_type');
            }
            if (! Schema::hasColumn('transactions', 'subtotal')) {
                $table->decimal('subtotal', 14, 2)->default(0)->after('member_number');
            }
            if (! Schema::hasColumn('transactions', 'discount')) {
                $table->decimal('discount', 14, 2)->default(0)->after('subtotal');
            }
            if (! Schema::hasColumn('transactions', 'tax')) {
                $table->decimal('tax', 14, 2)->default(0)->after('discount');
            }
            if (! Schema::hasColumn('transactions', 'other_fee')) {
                $table->decimal('other_fee', 14, 2)->default(0)->after('tax');
            }
            if (! Schema::hasColumn('transactions', 'grand_total')) {
                $table->decimal('grand_total', 14, 2)->default(0)->after('other_fee');
            }
            if (! Schema::hasColumn('transactions', 'paid_amount')) {
                $table->decimal('paid_amount', 14, 2)->default(0)->after('grand_total');
            }
            if (! Schema::hasColumn('transactions', 'change_amount')) {
                $table->decimal('change_amount', 14, 2)->default(0)->after('paid_amount');
            }
            if (! Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('change_amount');
            }
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            if (! Schema::hasColumn('transaction_details', 'product_code')) {
                $table->string('product_code')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('transaction_details', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_code');
            }
            if (! Schema::hasColumn('transaction_details', 'price')) {
                $table->decimal('price', 14, 2)->default(0)->after('product_name');
            }
            if (! Schema::hasColumn('transaction_details', 'discount')) {
                $table->decimal('discount', 14, 2)->default(0)->after('price');
            }
            if (! Schema::hasColumn('transaction_details', 'subtotal')) {
                $table->decimal('subtotal', 14, 2)->default(0)->after('discount');
            }
        });
    }

    public function down(): void
    {
        // Keep POS columns on rollback so recorded sales remain readable.
    }
};
