<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Address
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $company
 * @property string $mobile
 * @property string|null $phone
 * @property string $province_id
 * @property string $city_id
 * @property string $zipcode
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $city
 * @property-read mixed $province
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\AddressFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Query\Builder|Address onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|Address withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Address withoutTrashed()
 * @mixin \Eloquent
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @property-read string $role_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\AdminFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin hasRole($role)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUsername($value)
 */
	class Admin extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject {}
}

namespace App\Models{
/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $short_desc
 * @property int|null $image_id
 * @property string $body
 * @property mixed|null $meta
 * @property int $is_verified
 * @property int $article_category_id
 * @property int $admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $author
 * @property-read \App\Models\ArticleCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Image|null $image
 * @method static \Database\Factories\ArticleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereShortDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 */
	class Article extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ArticleCategory
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @method static \Database\Factories\ArticleCategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereSlug($value)
 */
	class ArticleCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Banner
 *
 * @property int $id
 * @property int|null $image_id
 * @property int $location
 * @property string|null $link
 * @property-read \App\Models\Image|null $image
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereLocation($value)
 */
	class Banner extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Cart
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $total_price
 * @property-read mixed $total_weight
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @mixin \Eloquent
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name
 * @property int $province_id
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereProvinceId($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $author_name
 * @property string $author_email
 * @property string $body
 * @property int $score
 * @property int $status
 * @property string $commentable_type
 * @property int $commentable_id
 * @property string $created_at
 * @property-read Model|\Eloquent $commentable
 * @property-read mixed $resource_type
 * @method static \Database\Factories\CommentFactory factory(...$parameters)
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereAuthorEmail($value)
 * @method static Builder|Comment whereAuthorName($value)
 * @method static Builder|Comment whereBody($value)
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereScore($value)
 * @method static Builder|Comment whereStatus($value)
 * @mixin \Eloquent
 * @property int $is_testimonial
 * @property-read string $created_at_fa
 * @property-read string $status_fa
 * @method static Builder|Comment testimonials()
 * @method static Builder|Comment whereIsTestimonial($value)
 * @property-read string $updated_at_fa
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DiscountCode
 *
 * @property int $id
 * @property string $code
 * @property int $is_suspended
 * @property int $discount_code_group_id
 * @property string|null $used_at
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode notUsed()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereDiscountCodeGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereIsSuspended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereUsedAt($value)
 */
	class DiscountCode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DiscountCodeGroup
 *
 * @property int $id
 * @property int|null $max
 * @property int|null $min
 * @property int|null $percent
 * @property int|null $value
 * @property string $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DiscountCode[] $discountCodes
 * @property-read int|null $discount_codes_count
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCodeGroup whereValue($value)
 */
	class DiscountCodeGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GuestOrder
 *
 * @property int $id
 * @property int $order_id
 * @property string $name
 * @property string|null $company
 * @property string $mobile
 * @property string|null $phone
 * @property string $province_id
 * @property string $city_id
 * @property string $zipcode
 * @property string $address
 * @property-read \App\Models\City $city
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Province $province
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GuestOrder whereZipcode($value)
 */
	class GuestOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Image
 *
 * @property int $id
 * @property string|null $alt
 * @property string $title
 * @property string|null $short_desc
 * @property string|null $desc
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $group
 * @property int $is_server_serve
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Banner[] $banners
 * @property-read int|null $banners_count
 * @property-read mixed $created_at_fa
 * @property-read mixed $updated_at_fa
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductCategory[] $productCategories
 * @property-read int|null $product_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductCategory[] $productCategoriesMobile
 * @property-read int|null $product_categories_mobile_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereIsServerServe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereShortDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUrl($value)
 */
	class Image extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Log
 *
 * @property int $id
 * @property string $visitor
 * @property string $referrer
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereReferrer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereVisitor($value)
 */
	class Log extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Message
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property string $created_at
 * @method static \Database\Factories\MessageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message wherePhone($value)
 * @mixin \Eloquent
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $status
 * @property string $code
 * @property int $delivery_cost
 * @property string|null $delivery_code
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $package_price
 * @property int|null $promo_code_id
 * @property-read \App\Models\Address $address
 * @property-read \App\Models\DiscountCode $discountCode
 * @property-read int $price
 * @property-read mixed $products_price
 * @property-read mixed $user
 * @property-read \App\Models\GuestOrder|null $guestOrder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $items
 * @property-read int|null $items_count
 * @property-read \App\Models\GuestOrder|null $purchasedByGuest
 * @property-read \App\Models\User $purchasedByUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReturnRequest[] $returnRequests
 * @property-read int|null $return_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\OrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Order filter(int $status)
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order paid()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order search(string $orderCode)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePackagePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePromoCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Phone
 *
 * @property int $id
 * @property string $phone
 * @property string $code
 * @property string $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Phone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Phone extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $short_desc
 * @property string $desc
 * @property mixed|null $meta_tags
 * @property int|null $image_id
 * @property int $off
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_best_selling
 * @property int|null $package_price
 * @property int $unit
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ProductCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read mixed $avg_score
 * @property-read \App\Models\Image|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product bestSelling()
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product hasDiscount()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product orderByPrice(string $ascOrDesc)
 * @method static \Illuminate\Database\Eloquent\Builder|Product orderByScore()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsBestSelling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePackagePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShortDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductCategory
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $image_id
 * @property int|null $image_mobile_id
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Image|null $image
 * @property-read \App\Models\Image|null $imageMobile
 * @property-read ProductCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\ProductCategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereImageMobileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductCategory withoutTrashed()
 */
	class ProductCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductItem
 *
 * @property int $id
 * @property float $weight
 * @property int $price
 * @property int $quantity
 * @property int|null $container
 * @property int $product_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read string|null $container_fa
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\ProductItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereContainer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductItem whereWeight($value)
 */
	class ProductItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PromoCode
 *
 * @property int $id
 * @property string $code
 * @property bool $user_only
 * @property bool $one_per_user
 * @property int|null $off_percent
 * @property int|null $off_value
 * @property int|null $max
 * @property int|null $min
 * @property bool $infinite
 * @property int|null $count
 * @property string $valid_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $valid_date_fa
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode newQuery()
 * @method static \Illuminate\Database\Query\Builder|PromoCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereInfinite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereOffPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereOffValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereOnePerUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereUserOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereValidDate($value)
 * @method static \Illuminate\Database\Query\Builder|PromoCode withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PromoCode withoutTrashed()
 */
	class PromoCode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Province
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereName($value)
 */
	class Province extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ReturnRequest
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property int $order_id
 * @property string|null $email
 * @property string $reason
 * @property string $created_at
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReturnRequest whereReason($value)
 */
	class ReturnRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $amount
 * @property int $order_id
 * @property string $authority
 * @property string|null $ref_id
 * @property int $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAuthority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\UserDetail|null $detail
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject {}
}

namespace App\Models{
/**
 * App\Models\UserDetail
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserDetailFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereUsername($value)
 * @mixin \Eloquent
 */
	class UserDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VerificationCode
 *
 * @property int $id
 * @property string $code
 * @property string $phone
 * @property int $usage
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode hasCode($code)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode hasPhone($phone)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode isLogin()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode isRegister()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationCode whereUsage($value)
 */
	class VerificationCode extends \Eloquent {}
}

