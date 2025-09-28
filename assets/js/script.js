class CartManager {
    constructor() {
        // نقطة النهاية (Endpoint) لجميع عمليات العربة
        this.apiEndpoint = 'cart_handler.php';
    }

    /**
     * دالة مساعدة لإرسال الطلبات إلى السيرفر.
     * @param {Object} data - البيانات المراد إرسالها (مثل action, product_id, quantity).
     * @returns {Promise<Object>} - الرد من السيرفر بصيغة JSON.
     */
    async sendRequest(data) {
        try {
            // نستخدم URLSearchParams لتشفير البيانات بشكل صحيح لتناسب POST
            const response = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });

            if (!response.ok) {
                throw new Error(`Network response was not ok, status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Fetch Error:', error);
            // إرجاع رسالة خطأ موحدة في حالة فشل الاتصال
            return { success: false, message: 'An error occurred while connecting to the server.' };
        }
    }

    /**
     * إضافة منتج إلى العربة.
     * @param {Object} productData - بيانات المنتج {id, quantity}.
     */
    async addToCart(productData) {
        const data = {
            action: 'add',
            product_id: productData.id,
            quantity: productData.quantity
            // يمكن إضافة اللون هنا إذا لزم الأمر
            // color: productData.color 
        };

        const result = await this.sendRequest(data);

        if (result.success) {
            this.updateCartCount(result.cart_count); // تحديث عداد العربة في الهيدر
            this.showSuccessModal(); // إظهار نافذة "تمت الإضافة بنجاح"
        } else {
            alert('Failed to add to cart: ' + (result.message || 'Unknown error'));
        }
    }
    
    /**
     * تحديث كمية منتج في العربة.
     * @param {number} itemId - معرف المنتج.
     * @param {number} newQuantity - الكمية الجديدة.
     */
    async updateQuantity(itemId, newQuantity) {
        // إذا كانت الكمية أقل من 1، تعامل معها كحذف
        if (newQuantity < 1) {
            openDeleteModal(itemId);
            return;
        }

        const data = {
            action: 'update',
            product_id: itemId,
            quantity: newQuantity
        };
        
        const result = await this.sendRequest(data);
        
        if (result.success) {
            // أسهل طريقة لضمان تحديث كل شيء (المنتج، ملخص الطلب) هي إعادة تحميل الصفحة
            location.reload();
        } else {
            alert('Failed to update quantity: ' + (result.message || 'Unknown error'));
        }
    }

    /**
     * إزالة منتج من العربة.
     * @param {number} itemId - معرف المنتج.
     */
    async removeFromCart(itemId) {
        const data = {
            action: 'remove',
            product_id: itemId
        };

        const result = await this.sendRequest(data);
        
        if (result.success) {
            location.reload();
        } else {
            alert('Failed to remove item: ' + (result.message || 'Unknown error'));
        }
    }

    /**
     * إفراغ العربة بالكامل.
     */
    async clearCart() {
        const data = { action: 'clear' };
        
        const result = await this.sendRequest(data);

        if (result.success) {
            location.reload();
        } else {
            alert('Failed to clear cart: ' + (result.message || 'Unknown error'));
        }
    }
    
    /**
     * تحديث رقم عدد المنتجات في أيقونة العربة بالهيدر.
     * @param {number} count - العدد الجديد للمنتجات.
     */
    updateCartCount(count) {
        const cartCount = document.getElementById('cartCount');
        if (cartCount) {
            cartCount.textContent = count;
        }
    }
    
    /**
     * إظهار نافذة Modal "تمت الإضافة بنجاح".
     */
    showSuccessModal() {
        const modalEl = document.getElementById('successModal');
        if (modalEl) {
            const successModal = new bootstrap.Modal(modalEl);
            successModal.show();
        }
    }
}

// إنشاء نسخة عالمية من مدير العربة
let cartManager;

// عند تحميل الصفحة بالكامل
document.addEventListener('DOMContentLoaded', function() {
    cartManager = new CartManager();
});


// ===============================================
// دوال خاصة بصفحة المنتج (product.php)
// ===============================================

function changeMainImage(thumbnail) {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.src = thumbnail.src.replace('w=100&h=100', 'w=600&h=600');
        document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
        thumbnail.classList.add('active');
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (quantityInput && parseInt(quantityInput.value) < 10) {
        quantityInput.value = parseInt(quantityInput.value) + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (quantityInput && parseInt(quantityInput.value) > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
    }
}

/**
 * دالة Add to Cart الرئيسية التي يتم استدعاؤها من product.php
 */
function addToCart() {
    // يجب أن يحتوي العنصر الرئيسي في صفحة المنتج على data-product-id
    const productContainer = document.querySelector('[data-product-id]');
    if (!productContainer) {
        console.error("Product ID not found on the page!");
        return;
    }
    
    const productId = productContainer.dataset.productId;
    const quantityInput = document.getElementById('quantity');

    const productData = {
        id: parseInt(productId),
        quantity: parseInt(quantityInput ? quantityInput.value : 1),
    };

    cartManager.addToCart(productData);
}

async function buyNow() {
    await addToCart(); // انتظر حتى تتم إضافة المنتج
    // بعد الإضافة بنجاح، يتم إظهار المودال تلقائيًا، ومنه يمكن للمستخدم الانتقال للعربة
    // أو يمكن توجيهه مباشرة بعد فترة قصيرة
    setTimeout(() => {
        window.location.href = 'cart.php';
    }, 500); // انتظر نصف ثانية قبل التوجيه
}


// ===============================================
// دوال خاصة بنوافذ Modal وصفحة العربة (cart.php)
// ===============================================

function goToCart() {
    window.location.href = 'cart.php';
}

function closeModal(modalId) {
    const modalEl = document.getElementById(modalId);
    if (modalEl) {
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
}

let itemToDelete = null;

function openDeleteModal(itemId) {
    itemToDelete = itemId;
    const modalEl = document.getElementById('deleteModal');
    if (modalEl) {
        const deleteModal = new bootstrap.Modal(modalEl);
        deleteModal.show();
    }
}

function confirmDelete() {
    if (itemToDelete !== null && cartManager) {
        cartManager.removeFromCart(itemToDelete);
        // لا داعي لإغلاق المودال يدويًا لأن الصفحة سيُعاد تحميلها
    }
}

function clearCart() {
    const modalEl = document.getElementById('clearCartModal');
    if (modalEl) {
        const clearCartModal = new bootstrap.Modal(modalEl);
        clearCartModal.show();
    }
}

function confirmClearCart() {
    if (cartManager) {
        cartManager.clearCart();
    }
}

function proceedToCheckout() {
    // ببساطة، يتم التوجيه إلى صفحة الدفع
    window.location.href = 'checkout.php';
}