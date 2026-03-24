import re

with open(r'c:\mortgage_minds\about-us.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the first card
pattern1 = r'<div class="col-lg-4 col-md-6">\s*<div class="fun-fact">\s*<div class="counter">.*?<div class="operator">M</div>.*?</div>\s*<div class="icon">\s*<i class="fas fa-home".*?</i>\s*</div>\s*<p>\s*We\'ve experience more than 10\+ years with success\.\s*</p>\s*</div>\s*</div>'
replacement1 = '''<div class="col-lg-4 col-md-6 mb-30 animate" data-animate="fadeInUp">
                        <div class="impact-card" style="background: rgba(1, 95, 201, 0.1); padding: 50px 30px; border-radius: 12px; border-top: 4px solid #015FC9; backdrop-filter: blur(10px); height: 100%; transition: all 0.3s ease; display: flex; flex-direction: column; align-items: center;">
                            <div class="icon" style="margin-bottom: 25px;">
                                <i class="fas fa-home" style="font-size: 3.5rem; color: #015FC9;"></i>
                            </div>
                            <h4 style="font-size: 1.4rem; color: #fff; margin-bottom: 15px; font-weight: 700;">Home Loans Approved</h4>
                            <p style="color: #d0d0d0; line-height: 1.7; margin: 0; font-size: 0.95rem;">
                                We have assisted customers in getting millions for mortgage funding with personalized solutions and professional guidance.
                            </p>
                        </div>
                    </div>'''

content = re.sub(pattern1, replacement1, content, flags=re.DOTALL)

# Replace third card (256K - happy clients)
pattern3 = r'<div class="col-lg-4 col-md-6">\s*<div class="fun-fact">\s*<div class="counter">.*?<div class="operator">K</div>.*?</div>\s*<div class="icon">\s*<img src="assets/img/icon/51\.png".*?</div>\s*<p>\s*We\'ve more than happy 3000\+ client all over the world\.\s*</p>\s*</div>\s*</div>'
replacement3 = '''<div class="col-lg-4 col-md-6 mb-30 animate" data-animate="fadeInUp">
                        <div class="impact-card" style="background: rgba(1, 95, 201, 0.1); padding: 50px 30px; border-radius: 12px; border-top: 4px solid #015FC9; backdrop-filter: blur(10px); height: 100%; transition: all 0.3s ease; display: flex; flex-direction: column; align-items: center;">
                            <div class="icon" style="margin-bottom: 25px;">
                                <i class="fas fa-heart" style="font-size: 3.5rem; color: #015FC9;"></i>
                            </div>
                            <h4 style="font-size: 1.4rem; color: #fff; margin-bottom: 15px; font-weight: 700;">Happy Homeowners</h4>
                            <p style="color: #d0d0d0; line-height: 1.7; margin: 0; font-size: 0.95rem;">
                                We have helped hundreds of clients in achieving their dream property goals.
                            </p>
                        </div>
                    </div>'''

content = re.sub(pattern3, replacement3, content, flags=re.DOTALL)

# Update second card to match new styling
pattern2 = r'<div class="col-lg-4 col-md-6 mb-30 animate" data-animate="fadeInUp">\s*<div class="fun-fact" style="background: rgba\(37, 205, 199, 0\.1\);[^"]*">\s*<div class="icon"[^>]*>\s*<i class="fas fa-briefcase"[^>]*></i>\s*</div>\s*<h4[^>]*>Industry Experience</h4>\s*<p[^>]*>Our qualified mortgage advisors are the ones who have deep knowledge of the New Zealand lending market\.</p>\s*</div>\s*</div>'
replacement2 = '''<div class="col-lg-4 col-md-6 mb-30 animate" data-animate="fadeInUp">
                        <div class="impact-card" style="background: rgba(37, 205, 199, 0.1); padding: 50px 30px; border-radius: 12px; border-top: 4px solid #25cdc7; backdrop-filter: blur(10px); height: 100%; transition: all 0.3s ease; display: flex; flex-direction: column; align-items: center;">
                            <div class="icon" style="margin-bottom: 25px;">
                                <i class="fas fa-briefcase" style="font-size: 3.5rem; color: #25cdc7;"></i>
                            </div>
                            <h4 style="font-size: 1.4rem; color: #fff; margin-bottom: 15px; font-weight: 700;">Industry Experience</h4>
                            <p style="color: #d0d0d0; line-height: 1.7; margin: 0; font-size: 0.95rem;">
                                Our qualified mortgage advisors are the ones who have deep knowledge of the New Zealand lending market.
                            </p>
                        </div>
                    </div>'''

content = re.sub(pattern2, replacement2, content, flags=re.DOTALL)

with open(r'c:\mortgage_minds\about-us.html', 'w', encoding='utf-8') as f:
    f.write(content)

print('History section updated successfully!')
