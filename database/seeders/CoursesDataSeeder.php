<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CoursesDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $courses = [
            ['title'=>'Wholesale ( Amazon, Walmart & eBay )','slug'=>'wholesale-amazon-walmart-ebay','price'=>24000,'batch_month'=>'1st','b24_course_id'=>1301,'status'=>'active'],
            ['title'=>'UAE ECommerce (Shopify Dropshipping)','slug'=>'uae-ecommerce-shopify-dropshipping','price'=>24000,'batch_month'=>'1st','b24_course_id'=>1305,'status'=>'active'],
            ['title'=>'Web Development','slug'=>'web-development','price'=>24000,'batch_month'=>'1st','b24_course_id'=>1309,'status'=>'active'],
            ['title'=>'Content Writing','slug'=>'content-writing','price'=>24000,'batch_month'=>'1st','b24_course_id'=>1313,'status'=>'active'],
            ['title'=>'Email Marketing (Cold Email Outreach)','slug'=>'email-marketing-cold-email-outreach','price'=>24000,'batch_month'=>'1st','b24_course_id'=>1317,'status'=>'active'],
            ['title'=>'Kindle','slug'=>'kindle','price'=>24000,'batch_month'=>'1st','b24_course_id'=>1321,'status'=>'active'],
            ['title'=>'Legal','slug'=>'legal','price'=>24000,'batch_month'=>'1st','b24_course_id'=>1325,'status'=>'active'],
            ['title'=>'Amazon PL','slug'=>'amazon-pl','price'=>24000,'batch_month'=>'2nd','b24_course_id'=>1329,'status'=>'active'],
            ['title'=>'Tiktok Shop','slug'=>'tiktok-shop','price'=>24000,'batch_month'=>'2nd','b24_course_id'=>1333,'status'=>'active'],
            ['title'=>'Local Ecommerce','slug'=>'local-ecommerce','price'=>24000,'batch_month'=>'2nd','b24_course_id'=>1337,'status'=>'active'],
            ['title'=>'Graphic Designing','slug'=>'graphic-designing','price'=>24000,'batch_month'=>'2nd','b24_course_id'=>1341,'status'=>'active'],
            ['title'=>'Walmart','slug'=>'walmart','price'=>24000,'batch_month'=>'2nd','b24_course_id'=>1345,'status'=>'active'],
            ['title'=>'LinkedIn Marketing / Social Media Marketing','slug'=>'linkedin-marketing-social-media-marketing','price'=>24000,'batch_month'=>'2nd','b24_course_id'=>1349,'status'=>'active'],
            ['title'=>'Digital Marketing','slug'=>'digital-marketing','price'=>24000,'batch_month'=>'3rd','b24_course_id'=>1353,'status'=>'active'],
            ['title'=>'Freelancing (Mastering Client Hunting & Retentio)','slug'=>'freelancing-mastering-client-hunting-and-retentio','price'=>24000,'batch_month'=>'3rd','b24_course_id'=>1357,'status'=>'active'],
            ['title'=>'Shopify','slug'=>'shopify','price'=>24000,'batch_month'=>'3rd','b24_course_id'=>1361,'status'=>'active'],
            ['title'=>'Etsy','slug'=>'etsy','price'=>24000,'batch_month'=>'3rd','b24_course_id'=>1365,'status'=>'active'],
            ['title'=>'Umrah Consultant','slug'=>'umrah-consultant','price'=>24000,'batch_month'=>'3rd','b24_course_id'=>1369,'status'=>'active'],
            ['title'=>'Ebay','slug'=>'ebay','price'=>24000,'batch_month'=>'3rd','b24_course_id'=>1373,'status'=>'active'],
        ];


        // Insert shifts, timings, and charges for each city
        foreach ($courses as $key => $value) {
            DB::table('tbl_courses')->insert(['title' => $value['title'], 'slug' => $value['slug'], 'price' => $value['price'],'batch_month' => $value['batch_month'],'status' => $value['status'] ]);
        }


    }
}
