<!-- Drawing Component -->
<div id="draw-content" class="tool-content">
    <div class="drawing-workspace">
        <div class="tools-panel">
            <div class="tool-group">
                <button class="tool-btn" onclick="setTool('brush')" title="Brush">
                    <i class="fas fa-paint-brush"></i>
                </button>
                <button class="tool-btn" onclick="setTool('eraser')" title="Eraser">
                    <i class="fas fa-eraser"></i>
                </button>
                <button class="tool-btn" onclick="setTool('line')" title="Line">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="tool-btn" onclick="setTool('rectangle')" title="Rectangle">
                    <i class="far fa-square"></i>
                </button>
                <button class="tool-btn" onclick="setTool('circle')" title="Circle">
                    <i class="far fa-circle"></i>
                </button>
                <button class="tool-btn" onclick="setTool('text')" title="Text">
                    <i class="fas fa-font"></i>
                </button>
            </div>

            <div class="tool-group">
                <div class="color-control">
                    <label>Color</label>
                    <input type="color" id="colorPicker" value="#000000" onchange="updateColor(this.value)">
                </div>
                <div class="brush-control">
                    <label>Size</label>
                    <input type="range" id="brushSize" min="1" max="50" value="5" onchange="updateBrushSize(this.value)">
                    <span id="brushSizeValue">5px</span>
                </div>
            </div>

            <div class="tool-group">
                <button class="tool-btn" onclick="undo()" title="Undo">
                    <i class="fas fa-undo"></i>
                </button>
                <button class="tool-btn" onclick="redo()" title="Redo">
                    <i class="fas fa-redo"></i>
                </button>
                <button class="tool-btn" onclick="clearCanvas()" title="Clear">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>

        <div class="canvas-container">
            <canvas id="drawingCanvas"></canvas>
        </div>

        <div class="drawing-actions">
            <button class="action-btn" onclick="saveToGallery()">
                <i class="fas fa-save"></i>
                Save to Gallery
            </button>
            <button class="action-btn" onclick="downloadCanvas()">
                <i class="fas fa-download"></i>
                Download
            </button>
            <div class="format-selector">
                <select id="downloadFormat">
                    <option value="png">PNG</option>
                    <option value="jpg">JPG</option>
                    <option value="svg">SVG</option>
                </select>
            </div>
        </div>
    </div>

    <div class="drawing-gallery">
        <h3>Your Gallery</h3>
        <div id="savedDrawings" class="gallery-grid"></div>
    </div>
</div>

<style>
.drawing-workspace {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.tools-panel {
    display: flex;
    gap: 2rem;
    padding: 1rem;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.tool-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border-right: 1px solid #eee;
}

.tool-group:last-child {
    border-right: none;
}

.tool-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    color: #2c3e50;
    transition: all 0.2s;
}

.tool-btn:hover {
    background: #e9ecef;
}

.tool-btn.active {
    background: #3498db;
    color: white;
}

.color-control, .brush-control {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.color-control label, .brush-control label {
    font-size: 0.8rem;
    color: #666;
}

#colorPicker {
    width: 40px;
    height: 40px;
    padding: 0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

#brushSize {
    width: 120px;
}

.canvas-container {
    padding: 1rem;
    display: flex;
    justify-content: center;
    background: #fff;
}

#drawingCanvas {
    border: 1px solid #eee;
    border-radius: 8px;
    cursor: crosshair;
}

.drawing-actions {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    background: #3498db;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn:hover {
    background: #2980b9;
}

.format-selector select {
    padding: 0.75rem;
    border-radius: 8px;
    border: 1px solid #eee;
    background: white;
    cursor: pointer;
}

.drawing-gallery {
    margin-top: 2rem;
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.drawing-gallery h3 {
    margin-bottom: 1rem;
    color: #2c3e50;
    font-size: 1.2rem;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.gallery-item {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s;
}

.gallery-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.gallery-item-actions {
    padding: 0.5rem;
    display: flex;
    justify-content: space-between;
    background: #f8f9fa;
}

.gallery-item-actions button {
    padding: 0.25rem 0.5rem;
    border: none;
    background: transparent;
    color: #666;
    cursor: pointer;
}

.gallery-item-actions button:hover {
    color: #3498db;
}
</style>

<script>
let canvas, ctx;
let isDrawing = false;
let currentTool = 'brush';
let lastX = 0;
let lastY = 0;
let color = '#000000';
let brushSize = 5;
let undoStack = [];
let redoStack = [];

document.addEventListener('DOMContentLoaded', function() {
    initializeCanvas();
});

function initializeCanvas() {
    canvas = document.getElementById('drawingCanvas');
    ctx = canvas.getContext('2d');
    
    // Set canvas size
    canvas.width = 800;
    canvas.height = 600;
    
    // Set initial styles
    ctx.strokeStyle = color;
    ctx.lineWidth = brushSize;
    ctx.lineCap = 'round';
    
    // Add event listeners
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Save initial state
    saveState();
}

function setTool(tool) {
    currentTool = tool;
    document.querySelectorAll('.tool-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`[onclick="setTool('${tool}')"]`).classList.add('active');
}

function updateColor(newColor) {
    color = newColor;
    ctx.strokeStyle = color;
}

function updateBrushSize(size) {
    brushSize = size;
    ctx.lineWidth = size;
    document.getElementById('brushSizeValue').textContent = size + 'px';
}

function startDrawing(e) {
    isDrawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

function draw(e) {
    if (!isDrawing) return;
    
    ctx.beginPath();
    
    switch(currentTool) {
        case 'brush':
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            break;
            
        case 'eraser':
            ctx.save();
            ctx.strokeStyle = '#ffffff';
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            ctx.restore();
            break;
            
        case 'line':
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            restoreState();
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            break;
            
        case 'rectangle':
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            restoreState();
            const width = e.offsetX - lastX;
            const height = e.offsetY - lastY;
            ctx.strokeRect(lastX, lastY, width, height);
            break;
            
        case 'circle':
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            restoreState();
            const radius = Math.sqrt(Math.pow(e.offsetX - lastX, 2) + Math.pow(e.offsetY - lastY, 2));
            ctx.beginPath();
            ctx.arc(lastX, lastY, radius, 0, Math.PI * 2);
            ctx.stroke();
            break;
    }
    
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        saveState();
    }
}

function saveState() {
    undoStack.push(canvas.toDataURL());
    redoStack = [];
}

function restoreState() {
    if (undoStack.length > 0) {
        const img = new Image();
        img.src = undoStack[undoStack.length - 1];
        img.onload = () => ctx.drawImage(img, 0, 0);
    }
}

function undo() {
    if (undoStack.length > 1) {
        redoStack.push(undoStack.pop());
        const img = new Image();
        img.src = undoStack[undoStack.length - 1];
        img.onload = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
        };
    }
}

function redo() {
    if (redoStack.length > 0) {
        const img = new Image();
        img.src = redoStack[redoStack.length - 1];
        img.onload = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
            undoStack.push(redoStack.pop());
        };
    }
}

function clearCanvas() {
    if (confirm('Are you sure you want to clear the canvas?')) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        saveState();
    }
}

function downloadCanvas() {
    const format = document.getElementById('downloadFormat').value;
    const link = document.createElement('a');
    
    if (format === 'svg') {
        // Convert canvas to SVG
        const svgData = canvasToSVG();
        const blob = new Blob([svgData], { type: 'image/svg+xml' });
        link.href = URL.createObjectURL(blob);
        link.download = 'drawing.svg';
    } else {
        link.href = canvas.toDataURL(`image/${format}`);
        link.download = `drawing.${format}`;
    }
    
    link.click();
}

function saveToGallery() {
    const dataUrl = canvas.toDataURL();
    const gallery = document.getElementById('savedDrawings');
    
    const item = document.createElement('div');
    item.className = 'gallery-item';
    item.innerHTML = `
        <img src="${dataUrl}" alt="Saved drawing">
        <div class="gallery-item-actions">
            <button onclick="downloadGalleryItem(this)" title="Download">
                <i class="fas fa-download"></i>
            </button>
            <button onclick="deleteGalleryItem(this)" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    gallery.prepend(item);
    saveGalleryToLocalStorage();
}

function downloadGalleryItem(button) {
    const dataUrl = button.closest('.gallery-item').querySelector('img').src;
    const link = document.createElement('a');
    link.href = dataUrl;
    link.download = 'gallery-drawing.png';
    link.click();
}

function deleteGalleryItem(button) {
    if (confirm('Are you sure you want to delete this drawing?')) {
        button.closest('.gallery-item').remove();
        saveGalleryToLocalStorage();
    }
}

function saveGalleryToLocalStorage() {
    const gallery = document.getElementById('savedDrawings');
    const items = Array.from(gallery.getElementsByClassName('gallery-item')).map(item => {
        return item.querySelector('img').src;
    });
    localStorage.setItem('drawingGallery', JSON.stringify(items));
}

function loadGalleryFromLocalStorage() {
    const gallery = document.getElementById('savedDrawings');
    const items = JSON.parse(localStorage.getItem('drawingGallery') || '[]');
    
    items.forEach(dataUrl => {
        const item = document.createElement('div');
        item.className = 'gallery-item';
        item.innerHTML = `
            <img src="${dataUrl}" alt="Saved drawing">
            <div class="gallery-item-actions">
                <button onclick="downloadGalleryItem(this)" title="Download">
                    <i class="fas fa-download"></i>
                </button>
                <button onclick="deleteGalleryItem(this)" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        gallery.appendChild(item);
    });
}

// Initialize gallery on load
document.addEventListener('DOMContentLoaded', loadGalleryFromLocalStorage);
</script> <?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/components/draw.blade.php ENDPATH**/ ?>