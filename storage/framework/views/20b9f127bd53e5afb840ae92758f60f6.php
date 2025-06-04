<!-- Drawing Component -->
<div id="draw-content" class="tool-content">
    <div class="canvas-container">
        <canvas id="drawingCanvas" width="800" height="600"></canvas>
    </div>
    <div class="drawing-controls">
        <div class="control-group">
            <div class="color-control">
                <label>Color</label>
                <input type="color" id="colorPicker" value="#000000">
            </div>
            <div class="brush-control">
                <label>Brush Size</label>
                <input type="range" id="brushSize" min="1" max="20" value="5">
            </div>
        </div>
        <div class="action-buttons">
            <button class="tool-button" onclick="clearCanvas()">
                <i class="fas fa-trash-alt"></i>
                Clear
            </button>
            <button class="tool-button" onclick="downloadCanvas()">
                <i class="fas fa-download"></i>
                Save
            </button>
        </div>
    </div>
</div>

<style>
.canvas-container {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin-bottom: 1rem;
}

#drawingCanvas {
    border: 1px solid #eee;
    border-radius: 8px;
    cursor: crosshair;
}

.drawing-controls {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.control-group {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.color-control, .brush-control {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.color-control label, .brush-control label {
    font-size: 0.9rem;
    color: #666;
}

#colorPicker {
    width: 50px;
    height: 50px;
    padding: 0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

#brushSize {
    width: 150px;
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.tool-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    background: white;
    color: #666;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.tool-button:hover {
    background: #f8f9fa;
    color: #333;
}

.tool-button i {
    font-size: 0.9rem;
}
</style> <?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/components/draw.blade.php ENDPATH**/ ?>