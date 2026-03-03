/**
 * Mouse-reactive gradient background
 * Entire background responds to mouse movement with neon pink cursor effect
 */

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    let mouseX = 50;
    let mouseY = 50;
    let currentX = 50;
    let currentY = 50;
    let isAnimating = false;

    // Linear interpolation for smooth movement
    const lerp = (start, end, factor) => start + (end - start) * factor;

    // Track mouse position
    document.addEventListener('mousemove', (e) => {
        mouseX = (e.clientX / window.innerWidth) * 100;
        mouseY = (e.clientY / window.innerHeight) * 100;
        
        if (!isAnimating) {
            isAnimating = true;
            animate();
        }
    });

    function animate() {
        // Smooth interpolation for position
        currentX = lerp(currentX, mouseX, 0.15);
        currentY = lerp(currentY, mouseY, 0.15);
        
        updateGradient();
        
        // Continue animating if motion is needed
        const distance = Math.abs(mouseX - currentX) + Math.abs(mouseY - currentY);
        if (distance > 0.5) {
            requestAnimationFrame(animate);
        } else {
            isAnimating = false;
        }
    }

    function updateGradient() {
        // Calculate dynamic gradient angle based on mouse position
        const baseAngle = 135;
        const angleVariation = ((currentX - 50) * 0.5) - ((currentY - 50) * 0.5);
        const gradientAngle = baseAngle + angleVariation;
        
        // Calculate color positions based on mouse position
        const distanceFromCenter = Math.sqrt(Math.pow(currentX - 50, 2) + Math.pow(currentY - 50, 2));
        const colorSpread = 30 + (distanceFromCenter * 0.5);
        
        const cyanPosition = Math.max(0, 50 - colorSpread);
        const pinkPosition = Math.min(100, 50 + colorSpread);
        
        // Neon pink cursor effect (always pink, regardless of position)
        body.style.background = `
            radial-gradient(circle 500px at ${currentX}% ${currentY}%, 
                #fb8ed5 0%, 
                rgba(251, 142, 213, 0.6) 40%,
                rgba(251, 142, 213, 0) 100%
            ),
            linear-gradient(${gradientAngle}deg, #2ed2fb ${cyanPosition}%, #fb8ed5 ${pinkPosition}%)
        `;
    }

    // Initialize gradient
    updateGradient();

    // Update on window resize
    window.addEventListener('resize', updateGradient);
});
