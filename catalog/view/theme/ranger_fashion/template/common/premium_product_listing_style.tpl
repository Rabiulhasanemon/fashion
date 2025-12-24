<style>
/* Premium Product Listing Styles - Applied to existing classes */
.main-content {
    padding: 40px 0;
    background: #fff;
}

.main-content .row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 25px;
    margin: 0;
    list-style: none;
}

.main-content .row > [class*="col-"] {
    padding: 0;
    margin: 0;
    width: 100%;
    max-width: 100%;
    flex: none;
}

.main-content .product-item {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
    margin-bottom: 0 !important;
}

.main-content .product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: rgba(255,107,157,0.2);
}

.main-content .product-item .mark {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 12px;
    z-index: 10;
    box-shadow: 0 3px 8px rgba(255,107,107,0.3);
    line-height: 1.2;
}

.main-content .product-img {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.main-content .product-img a {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    text-decoration: none;
}

.main-content .product-img img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.main-content .product-item:hover .product-img img {
    transform: scale(1.08);
}

.main-content .product-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.main-content .product-info a {
    text-decoration: none;
}

.main-content .product-info .name {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 12px 0;
    line-height: 1.4;
    min-height: 44px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.main-content .product-info .name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.main-content .product-info .name a:hover {
    color: #10503D;
}

.main-content .product-price-wrap {
    margin-bottom: 15px;
    margin-top: auto;
}

.main-content .product-price-wrap .price {
    font-size: 22px;
    font-weight: 700;
    color: #10503D;
    margin-right: 8px;
    display: inline-block;
}

.main-content .product-price-wrap .price.old {
    font-size: 16px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.main-content .product-btn-wrap {
    display: flex;
    gap: 10px;
    margin-top: auto;
}

.main-content .product-btn-wrap .btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    text-decoration: none;
    border: none;
    outline: none;
}

.main-content .product-btn-wrap .btn.wishlist {
    flex: 0 0 45px;
    background: #f0f0f0;
    color: #666;
    border: 1px solid #e0e0e0;
    min-width: 45px;
}

.main-content .product-btn-wrap .btn.wishlist:hover {
    background: #10503D;
    color: #fff;
    border-color: #10503D;
    transform: scale(1.05);
}

.main-content .product-btn-wrap .btn.wishlist svg {
    width: 20px;
    height: 20px;
    fill: currentColor;
}

.main-content .product-btn-wrap .btn.buy {
    flex: 1;
    background: linear-gradient(135deg, #10503D 0%, #A68A6A 100%);
    color: #fff;
    border: 1px solid transparent;
}

.main-content .product-btn-wrap .btn.buy:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,107,157,0.3);
}

.main-content .product-btn-wrap .btn.buy .material-icons {
    font-size: 18px;
}

/* Responsive Design */
@media (max-width: 992px) {
    .main-content .row {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .main-content {
        padding: 30px 0;
    }
    
    .main-content .row {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .main-content .product-item:hover {
        transform: none;
    }
    
    .main-content .product-info {
        padding: 15px;
    }
    
    .main-content .product-info .name {
        font-size: 14px;
        min-height: 40px;
    }
    
    .main-content .product-price-wrap .price {
        font-size: 20px;
    }
    
    .main-content .product-btn-wrap .btn {
        padding: 10px;
        font-size: 13px;
    }
    
    .main-content .product-btn-wrap .btn.wishlist {
        flex: 0 0 40px;
        min-width: 40px;
    }
}

@media (max-width: 480px) {
    .main-content .row {
        gap: 12px;
    }
    
    .main-content .product-info .name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .main-content .product-price-wrap .price {
        font-size: 18px;
    }
    
    .main-content .product-price-wrap .price.old {
        font-size: 14px;
    }
    
    .main-content .product-btn-wrap .btn {
        padding: 8px;
        font-size: 12px;
    }
    
    .main-content .product-btn-wrap .btn.wishlist {
        flex: 0 0 36px;
        min-width: 36px;
    }
    
    .main-content .product-btn-wrap .btn.wishlist svg {
        width: 16px;
        height: 16px;
    }
    
    .main-content .product-btn-wrap .btn.buy .material-icons {
        font-size: 16px;
    }
}

/* Accessibility */
.main-content .product-btn-wrap .btn:focus {
    outline: 2px solid #10503D;
    outline-offset: 2px;
}
</style>



