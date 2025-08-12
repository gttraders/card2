import React, { useState, useEffect } from 'react';
import { Phone, MessageCircle, MapPin, Mail, Globe, Download, Share2, Users, ShoppingCart, Star, Play, ChevronLeft, ChevronRight, Menu, X } from 'lucide-react';

interface Product {
  id: number;
  name: string;
  price: number;
  discountPrice?: number;
  image: string;
  description: string;
  inStock: boolean;
}

interface CartItem extends Product {
  quantity: number;
}

interface Review {
  id: number;
  name: string;
  rating: number;
  comment: string;
  approved: boolean;
}

interface Theme {
  id: string;
  name: string;
  primary: string;
  secondary: string;
  accent: string;
  background: string;
  cardBg: string;
  text: string;
  textSecondary: string;
}

const themes: Theme[] = [
  {
    id: 'blue-dark',
    name: 'Professional Blue',
    primary: '#1e40af',
    secondary: '#0891b2',
    accent: '#f97316',
    background: 'linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0891b2 100%)',
    cardBg: 'rgba(255, 255, 255, 0.1)',
    text: '#ffffff',
    textSecondary: '#e2e8f0'
  },
  {
    id: 'gradient',
    name: 'Vibrant Gradient',
    primary: '#8b5cf6',
    secondary: '#06b6d4',
    accent: '#f59e0b',
    background: 'linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%)',
    cardBg: 'rgba(255, 255, 255, 0.15)',
    text: '#ffffff',
    textSecondary: '#f1f5f9'
  },
  {
    id: 'teal-orange',
    name: 'Modern Teal',
    primary: '#0d9488',
    secondary: '#0891b2',
    accent: '#f97316',
    background: 'linear-gradient(135deg, #0f766e 0%, #0891b2 50%, #f59e0b 100%)',
    cardBg: 'rgba(255, 255, 255, 0.12)',
    text: '#ffffff',
    textSecondary: '#e2e8f0'
  },
  {
    id: 'light',
    name: 'Clean Light',
    primary: '#2563eb',
    secondary: '#0891b2',
    accent: '#f97316',
    background: 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)',
    cardBg: '#ffffff',
    text: '#1e293b',
    textSecondary: '#64748b'
  }
];

function App() {
  const [currentTheme, setCurrentTheme] = useState(themes[0]);
  const [cart, setCart] = useState<CartItem[]>([]);
  const [showCart, setShowCart] = useState(false);
  const [currentBanner, setCurrentBanner] = useState(0);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [viewCount, setViewCount] = useState(1521);
  
  // Sample data
  const banners = [
    'https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=800',
    'https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=800',
    'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=800'
  ];

  const products: Product[] = [
    {
      id: 1,
      name: 'Premium Business Card',
      price: 500,
      discountPrice: 399,
      image: 'https://images.pexels.com/photos/6289065/pexels-photo-6289065.jpeg?auto=compress&cs=tinysrgb&w=400',
      description: 'High-quality business cards with premium finish',
      inStock: true
    },
    {
      id: 2,
      name: 'Digital Visiting Card',
      price: 299,
      image: 'https://images.pexels.com/photos/6289025/pexels-photo-6289025.jpeg?auto=compress&cs=tinysrgb&w=400',
      description: 'Modern digital visiting card solution',
      inStock: true
    },
    {
      id: 3,
      name: 'Corporate Branding Package',
      price: 2999,
      discountPrice: 1999,
      image: 'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg?auto=compress&cs=tinysrgb&w=400',
      description: 'Complete corporate branding solution',
      inStock: true
    },
    {
      id: 4,
      name: 'Logo Design Service',
      price: 1500,
      image: 'https://images.pexels.com/photos/3184432/pexels-photo-3184432.jpeg?auto=compress&cs=tinysrgb&w=400',
      description: 'Professional logo design service',
      inStock: true
    }
  ];

  const reviews: Review[] = [
    {
      id: 1,
      name: 'Rajesh Kumar',
      rating: 5,
      comment: 'Excellent service and professional quality work. Highly recommended!',
      approved: true
    },
    {
      id: 2,
      name: 'Priya Singh',
      rating: 4,
      comment: 'Great experience with their team. Very responsive and helpful.',
      approved: true
    }
  ];

  const videoLinks = [
    'https://www.youtube.com/embed/dQw4w9WgXcQ',
    'https://www.youtube.com/embed/jNQXAC9IVRw'
  ];

  // Auto-scroll banners
  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentBanner((prev) => (prev + 1) % banners.length);
    }, 2000);
    return () => clearInterval(interval);
  }, [banners.length]);

  const addToCart = (product: Product) => {
    setCart(prev => {
      const existing = prev.find(item => item.id === product.id);
      if (existing) {
        return prev.map(item =>
          item.id === product.id
            ? { ...item, quantity: item.quantity + 1 }
            : item
        );
      }
      return [...prev, { ...product, quantity: 1 }];
    });
  };

  const removeFromCart = (productId: number) => {
    setCart(prev => prev.filter(item => item.id !== productId));
  };

  const updateQuantity = (productId: number, quantity: number) => {
    if (quantity === 0) {
      removeFromCart(productId);
      return;
    }
    setCart(prev =>
      prev.map(item =>
        item.id === productId ? { ...item, quantity } : item
      )
    );
  };

  const getTotalItems = () => cart.reduce((sum, item) => sum + item.quantity, 0);
  const getTotalPrice = () => cart.reduce((sum, item) => sum + (item.discountPrice || item.price) * item.quantity, 0);

  const handleWhatsAppShare = (countryCode: string, message: string = '') => {
    const url = `https://wa.me/${countryCode}?text=${encodeURIComponent(message || 'Hello! I found your visiting card and would like to connect.')}`;
    window.open(url, '_blank');
  };

  const generateVCF = () => {
    const vcf = `BEGIN:VCARD
VERSION:3.0
FN:Vishal Rathod
TITLE:FOUNDER
ORG:DEMO CARD
TEL:+91-9765834383
EMAIL:info@galaxytribes.in
ADR:;;Nashik;;;India;
URL:https://galaxytribes.in
END:VCARD`;
    
    const blob = new Blob([vcf], { type: 'text/vcard' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'contact.vcf';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  };

  const handleShare = () => {
    if (navigator.share) {
      navigator.share({
        title: 'DEMO CARD - Vishal Rathod',
        text: 'Check out my digital visiting card',
        url: window.location.href,
      });
    } else {
      navigator.clipboard.writeText(window.location.href);
      alert('Link copied to clipboard!');
    }
  };

  const ContactButton = ({ icon: Icon, label, onClick, className = '' }: any) => (
    <button
      onClick={onClick}
      className={`flex items-center justify-center px-4 py-3 rounded-full font-semibold text-sm transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95 ${className}`}
    >
      <Icon size={16} className="mr-2" />
      {label}
    </button>
  );

  return (
    <div 
      className="min-h-screen transition-all duration-500"
      style={{ 
        background: currentTheme.background,
        color: currentTheme.text 
      }}
    >
      {/* Auto-scrolling Top Banner */}
      <div className="relative h-24 md:h-32 overflow-hidden">
        <div 
          className="flex transition-transform duration-500 ease-in-out h-full"
          style={{ transform: `translateX(-${currentBanner * 100}%)` }}
        >
          {banners.map((banner, index) => (
            <img
              key={index}
              src={banner}
              alt={`Banner ${index + 1}`}
              className="w-full h-full object-cover flex-shrink-0"
            />
          ))}
        </div>
        <div className="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm flex items-center">
          <Users size={14} className="mr-1" />
          {viewCount}
        </div>
      </div>

      {/* Header Section */}
      <div className="container mx-auto px-4 py-8 text-center">
        {/* Logo */}
        <div className="mb-6">
          <div 
            className="w-24 h-24 mx-auto rounded-2xl flex items-center justify-center text-4xl font-bold shadow-lg"
            style={{ backgroundColor: currentTheme.cardBg }}
          >
            🏢
          </div>
        </div>

        {/* Company Title & Director */}
        <h1 className="text-3xl md:text-4xl font-bold mb-2">DEMO CARD</h1>
        <h2 className="text-lg md:text-xl mb-1" style={{ color: currentTheme.textSecondary }}>
          DEMO CARD Vishal Rathod
        </h2>
        <p className="text-sm md:text-base opacity-80">FOUNDER</p>

        {/* Action Buttons */}
        <div className="grid grid-cols-2 md:grid-cols-5 gap-4 mt-8 mb-8">
          <ContactButton
            icon={Phone}
            label="Call"
            onClick={() => window.open('tel:+919765834383')}
            className="bg-blue-500 hover:bg-blue-600 text-white"
          />
          <ContactButton
            icon={MessageCircle}
            label="WhatsApp"
            onClick={() => handleWhatsAppShare('919765834383')}
            className="bg-green-500 hover:bg-green-600 text-white"
          />
          <ContactButton
            icon={MapPin}
            label="Direction"
            onClick={() => window.open('https://maps.google.com/?q=Nashik')}
            className="bg-red-500 hover:bg-red-600 text-white"
          />
          <ContactButton
            icon={Mail}
            label="Mail"
            onClick={() => window.open('mailto:info@galaxytribes.in')}
            className="bg-purple-500 hover:bg-purple-600 text-white"
          />
          <ContactButton
            icon={Globe}
            label="Website"
            onClick={() => window.open('https://galaxytribes.in')}
            className="bg-indigo-500 hover:bg-indigo-600 text-white col-span-2 md:col-span-1"
          />
        </div>

        {/* Contact Details */}
        <div className="space-y-3 mb-8">
          {[
            { icon: Phone, text: '9765834383' },
            { icon: Phone, text: '9765834383' },
            { icon: Mail, text: 'info@galaxytribes.in' },
            { icon: MapPin, text: 'Nashik' }
          ].map((contact, index) => (
            <div 
              key={index}
              className="flex items-center justify-center p-3 rounded-lg"
              style={{ backgroundColor: currentTheme.cardBg }}
            >
              <contact.icon size={20} className="mr-3" style={{ color: currentTheme.accent }} />
              <span>{contact.text}</span>
            </div>
          ))}
        </div>

        {/* Share Section */}
        <div className="mb-8">
          <div className="flex mb-4">
            <input
              type="text"
              placeholder="+91"
              className="flex-1 p-3 rounded-l-lg border-0 text-black"
              defaultValue="+91"
            />
            <button 
              className="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-r-lg transition-colors duration-300"
              onClick={() => handleWhatsAppShare('91')}
            >
              <MessageCircle size={20} className="inline mr-2" />
              Share on WhatsApp
            </button>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button 
              onClick={generateVCF}
              className="flex items-center justify-center p-3 rounded-lg transition-all duration-300 hover:scale-105"
              style={{ backgroundColor: currentTheme.cardBg }}
            >
              <Download size={20} className="mr-2" />
              Save to Contacts
            </button>
            <button 
              onClick={handleShare}
              className="flex items-center justify-center p-3 rounded-lg transition-all duration-300 hover:scale-105"
              style={{ backgroundColor: currentTheme.cardBg }}
            >
              <Share2 size={20} className="mr-2" />
              Share
            </button>
            <button 
              className="flex items-center justify-center p-3 rounded-lg transition-all duration-300 hover:scale-105"
              style={{ backgroundColor: currentTheme.cardBg }}
            >
              <Download size={20} className="mr-2" />
              Save PDF
            </button>
          </div>
        </div>

        {/* Social Media Icons */}
        <div className="grid grid-cols-4 md:grid-cols-8 gap-4 mb-8">
          {[
            { name: 'Facebook', color: '#1877f2', icon: '📘' },
            { name: 'YouTube', color: '#ff0000', icon: '📺' },
            { name: 'Twitter', color: '#1da1f2', icon: '🐦' },
            { name: 'Instagram', color: '#e4405f', icon: '📷' },
            { name: 'LinkedIn', color: '#0077b5', icon: '💼' },
            { name: 'Pinterest', color: '#bd081c', icon: '📌' },
            { name: 'Telegram', color: '#0088cc', icon: '💌' },
            { name: 'Zomato', color: '#e23744', icon: '🍽️' }
          ].map((social, index) => (
            <button
              key={index}
              className="w-12 h-12 rounded-full flex items-center justify-center text-xl transition-all duration-300 hover:scale-110 shadow-lg"
              style={{ backgroundColor: social.color }}
            >
              {social.icon}
            </button>
          ))}
        </div>

        {/* Theme Selector */}
        <div className="mb-8">
          <h3 className="text-lg font-semibold mb-4">Change Theme</h3>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
            {themes.map((theme) => (
              <button
                key={theme.id}
                onClick={() => setCurrentTheme(theme)}
                className={`p-3 rounded-lg text-sm font-medium transition-all duration-300 ${
                  currentTheme.id === theme.id 
                    ? 'ring-2 ring-white' 
                    : 'hover:scale-105'
                }`}
                style={{ 
                  background: theme.background,
                  color: theme.text
                }}
              >
                {theme.name}
              </button>
            ))}
          </div>
        </div>

        {/* QR Code Section */}
        <div 
          className="p-6 rounded-lg mb-8"
          style={{ backgroundColor: currentTheme.cardBg }}
        >
          <h3 className="text-lg font-semibold mb-4">Scan QR Code to go to Visiting Card</h3>
          <div className="w-48 h-48 mx-auto bg-white p-4 rounded-lg">
            <div className="w-full h-full bg-black flex items-center justify-center text-white text-xs">
              QR CODE
              <br />
              (Generated)
            </div>
          </div>
        </div>

        {/* PDF Downloads */}
        <div className="mb-8">
          <h3 className="text-lg font-semibold mb-4">Download Resources</h3>
          <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
            {['Brochure', 'Catalog', 'Price List', 'Profile', 'Portfolio'].map((pdf, index) => (
              <button
                key={index}
                className="flex items-center justify-center p-3 rounded-lg transition-all duration-300 hover:scale-105"
                style={{ backgroundColor: currentTheme.accent }}
              >
                <Download size={20} className="mr-2" />
                {pdf} PDF
              </button>
            ))}
          </div>
        </div>

        {/* Products Section */}
        <div className="mb-8">
          <h3 className="text-2xl font-semibold mb-6">Our Products</h3>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {products.map((product) => (
              <div
                key={product.id}
                className="rounded-lg overflow-hidden shadow-lg transition-all duration-300 hover:scale-105"
                style={{ backgroundColor: currentTheme.cardBg }}
              >
                <img
                  src={product.image}
                  alt={product.name}
                  className="w-full h-48 object-cover"
                />
                <div className="p-4">
                  <h4 className="font-semibold mb-2">{product.name}</h4>
                  <p className="text-sm opacity-80 mb-3">{product.description}</p>
                  <div className="flex items-center justify-between mb-3">
                    <div className="flex items-center space-x-2">
                      {product.discountPrice && (
                        <span className="line-through text-sm opacity-60">
                          ₹{product.price}
                        </span>
                      )}
                      <span className="font-bold text-lg" style={{ color: currentTheme.accent }}>
                        ₹{product.discountPrice || product.price}
                      </span>
                    </div>
                    <button
                      onClick={() => handleWhatsAppShare('919765834383', `Hi! I'm interested in ${product.name}`)}
                      className="text-green-500 hover:text-green-600 transition-colors duration-300"
                    >
                      <MessageCircle size={20} />
                    </button>
                  </div>
                  <button
                    onClick={() => addToCart(product)}
                    className="w-full py-2 px-4 rounded-lg font-semibold transition-all duration-300 hover:scale-105"
                    style={{ backgroundColor: currentTheme.primary, color: 'white' }}
                  >
                    ADD TO CART
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* YouTube Videos */}
        <div className="mb-8">
          <h3 className="text-2xl font-semibold mb-6">Video Gallery</h3>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {videoLinks.map((video, index) => (
              <div key={index} className="aspect-video rounded-lg overflow-hidden">
                <iframe
                  src={video}
                  title={`Video ${index + 1}`}
                  className="w-full h-full"
                  allowFullScreen
                />
              </div>
            ))}
          </div>
        </div>

        {/* Reviews Section */}
        <div className="mb-8">
          <h3 className="text-2xl font-semibold mb-6">Customer Reviews</h3>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {reviews.map((review) => (
              <div
                key={review.id}
                className="p-6 rounded-lg"
                style={{ backgroundColor: currentTheme.cardBg }}
              >
                <div className="flex items-center mb-3">
                  <div className="flex">
                    {[...Array(5)].map((_, i) => (
                      <Star
                        key={i}
                        size={16}
                        className={i < review.rating ? 'text-yellow-400 fill-current' : 'text-gray-400'}
                      />
                    ))}
                  </div>
                  <span className="ml-2 font-semibold">{review.name}</span>
                </div>
                <p className="text-sm opacity-80">{review.comment}</p>
              </div>
            ))}
          </div>

          {/* Add Review Form */}
          <div 
            className="p-6 rounded-lg"
            style={{ backgroundColor: currentTheme.cardBg }}
          >
            <h4 className="font-semibold mb-4">Add Your Review</h4>
            <div className="space-y-4">
              <div className="flex">
                {[...Array(5)].map((_, i) => (
                  <Star
                    key={i}
                    size={24}
                    className="text-gray-400 hover:text-yellow-400 cursor-pointer transition-colors duration-300"
                  />
                ))}
              </div>
              <input
                type="text"
                placeholder="Your Name"
                className="w-full p-3 rounded-lg text-black"
              />
              <input
                type="email"
                placeholder="Your Email"
                className="w-full p-3 rounded-lg text-black"
              />
              <textarea
                placeholder="Your Review"
                rows={4}
                className="w-full p-3 rounded-lg text-black resize-none"
              />
              <button
                className="w-full py-3 px-4 rounded-lg font-semibold transition-all duration-300 hover:scale-105"
                style={{ backgroundColor: currentTheme.primary, color: 'white' }}
              >
                Submit Review
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Auto-scrolling Bottom Banner */}
      <div className="relative h-24 md:h-32 overflow-hidden">
        <div 
          className="flex transition-transform duration-500 ease-in-out h-full"
          style={{ transform: `translateX(-${currentBanner * 100}%)` }}
        >
          {banners.map((banner, index) => (
            <img
              key={index}
              src={banner}
              alt={`Bottom Banner ${index + 1}`}
              className="w-full h-full object-cover flex-shrink-0"
            />
          ))}
        </div>
      </div>

      {/* Bottom Navigation */}
      <div 
        className="fixed bottom-0 left-0 right-0 grid grid-cols-5 md:grid-cols-9 gap-1 p-2 shadow-lg"
        style={{ backgroundColor: currentTheme.cardBg, backdropFilter: 'blur(10px)' }}
      >
        {[
          { icon: '🏠', label: 'Home' },
          { icon: '📋', label: 'About Us' },
          { icon: '🛍️', label: 'Product & Services' },
          { icon: '🛒', label: 'Shop' },
          { icon: '🖼️', label: 'Gallery' },
          { icon: '📺', label: 'YouTube Videos' },
          { icon: '💳', label: 'Payment' },
          { icon: '⭐', label: 'Feedback' },
          { icon: '📞', label: 'Contact Us' }
        ].map((item, index) => (
          <button
            key={index}
            className="flex flex-col items-center justify-center p-2 text-xs transition-all duration-300 hover:scale-105 active:scale-95"
          >
            <span className="text-lg mb-1">{item.icon}</span>
            <span className="hidden md:block">{item.label}</span>
          </button>
        ))}
      </div>

      {/* Floating Cart Button */}
      <button
        onClick={() => setShowCart(true)}
        className="fixed top-1/2 right-4 transform -translate-y-1/2 w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110 z-50"
        style={{ backgroundColor: currentTheme.accent }}
      >
        <ShoppingCart size={24} className="text-white" />
        {getTotalItems() > 0 && (
          <span className="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-6 h-6 rounded-full flex items-center justify-center">
            {getTotalItems()}
          </span>
        )}
      </button>

      {/* Cart Modal */}
      {showCart && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div 
            className="rounded-lg p-6 w-full max-w-md max-h-96 overflow-y-auto"
            style={{ backgroundColor: currentTheme.cardBg }}
          >
            <div className="flex items-center justify-between mb-4">
              <h3 className="text-lg font-semibold">Shopping Cart</h3>
              <button
                onClick={() => setShowCart(false)}
                className="text-gray-400 hover:text-white transition-colors duration-300"
              >
                <X size={24} />
              </button>
            </div>
            
            {cart.length === 0 ? (
              <p className="text-center opacity-60">Your cart is empty</p>
            ) : (
              <>
                <div className="space-y-4 mb-6">
                  {cart.map((item) => (
                    <div key={item.id} className="flex items-center space-x-4">
                      <img
                        src={item.image}
                        alt={item.name}
                        className="w-16 h-16 rounded-lg object-cover"
                      />
                      <div className="flex-1">
                        <h4 className="font-medium">{item.name}</h4>
                        <p className="text-sm opacity-60">₹{item.discountPrice || item.price}</p>
                      </div>
                      <div className="flex items-center space-x-2">
                        <button
                          onClick={() => updateQuantity(item.id, item.quantity - 1)}
                          className="w-8 h-8 rounded-full bg-gray-600 text-white flex items-center justify-center hover:bg-gray-700 transition-colors duration-300"
                        >
                          -
                        </button>
                        <span className="w-8 text-center">{item.quantity}</span>
                        <button
                          onClick={() => updateQuantity(item.id, item.quantity + 1)}
                          className="w-8 h-8 rounded-full bg-gray-600 text-white flex items-center justify-center hover:bg-gray-700 transition-colors duration-300"
                        >
                          +
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
                
                <div className="border-t pt-4" style={{ borderColor: currentTheme.textSecondary }}>
                  <div className="flex items-center justify-between mb-4">
                    <span className="font-semibold">Total: ₹{getTotalPrice()}</span>
                    <span className="text-sm opacity-60">({getTotalItems()} items)</span>
                  </div>
                  
                  <button
                    className="w-full py-3 px-4 rounded-lg font-semibold text-white transition-all duration-300 hover:scale-105"
                    style={{ backgroundColor: currentTheme.accent }}
                    onClick={() => {
                      const orderDetails = cart.map(item => `${item.name} x${item.quantity}`).join('\n');
                      const message = `Order Details:\n${orderDetails}\nTotal: ₹${getTotalPrice()}\n\nUPI Payment - Please provide UPI details.`;
                      handleWhatsAppShare('919765834383', message);
                    }}
                  >
                    PAY NOW via UPI
                  </button>
                </div>
              </>
            )}
          </div>
        </div>
      )}

      {/* Footer */}
      <div className="pb-20 pt-8 text-center" style={{ backgroundColor: currentTheme.cardBg }}>
        <p className="text-sm opacity-60">© 2025 DEMO CARD. All rights reserved.</p>
        <p className="text-xs opacity-40 mt-2">Made with ❤️ for digital business</p>
      </div>
    </div>
  );
}

export default App;