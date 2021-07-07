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
 * @property string|null $image
 * @property string $body
 * @property mixed|null $meta
 * @property int $is_verified
 * @property int $article_category_id
 * @property int $admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read mixed $thumbnail
 * @method static \Database\Factories\ArticleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereMeta($value)
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
 * @property string $image
 * @property int $location
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereImage($value)
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
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $children
 * @property-read int|null $children_count
 * @property-read mixed $icon
 * @property-read Category $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 */
	class Category extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read mixed $resource_type
 * @method static \Database\Factories\CommentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereAuthorEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereStatus($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DiscountCode
 *
 * @property int $id
 * @property string $code
 * @property int|null $max
 * @property int|null $min
 * @property int|null $percent
 * @property int|null $value
 * @property int|null $user_id
 * @property string $created_at
 * @property string $expires_at
 * @property string|null $used_at
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCode whereValue($value)
 */
	class DiscountCode extends \Eloquent {}
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
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $address_id
 * @property int|null $user_id
 * @property int $status
 * @property string $code
 * @property int $delivery_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Address $address
 * @property-read mixed $status_fa
 * @property-read mixed $total_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\OrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
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
 */
	class Phone extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Poster
 *
 * @property int $id
 * @property string $image
 * @property int $location
 * @property int $is_active
 * @method static \Database\Factories\PosterFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster query()
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poster whereLocation($value)
 */
	class Poster extends \Eloquent {}
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
 * @property string $image
 * @property int $off
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read mixed $avg_score
 * @property-read mixed $max_price
 * @property-read mixed $min_price
 * @property-read mixed $price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductItem[] $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product hasDiscount()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetaTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceIsGreater($price)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceIsLess($price)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShortDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
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
 * @property string $image
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductCategory[] $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\ProductCategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCategory whereUpdatedAt($value)
 */
	class ProductCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductFeature
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature lightest()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature plastic()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFeature zink()
 */
	class ProductFeature extends \Eloquent {}
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

