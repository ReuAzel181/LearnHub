@extends('layouts.app')

@section('content')
<div class="container">
    <h1 id="Title">LearnHub</h1>
    <div class="content-container">
        <div class="left-container">
            <div class="form-group">
                <h2>Add Category</h2>
                <form id="categoryForm">
                    @csrf
                    <input type="text" id="newCategory" placeholder="New Category">
                    <div class="color-picker-container">
                        <label for="categoryColor">Pick a color:</label>
                        <input type="color" id="categoryColor" value="#ffffff">
                    </div>
                    <button type="submit" class="btn">Add Category</button>
                </form>
            </div>
            <div class="category-list" id="categoryList">
                <!-- Categories will be listed here -->
            </div>
            <div id="deadlineContainer">
                <label for="deadline">Status:</label>
                <input type="text" id="deadline" name="deadline" placeholder="Set Date" readonly>
            </div>
            <div class="form-group">
                <h2>Add Module</h2>
                <form id="moduleForm" enctype="multipart/form-data">
                    @csrf
                    <input type="text" id="name" name="name" placeholder="Module Name" required>
                    <div id="thumbnailDropzone" class="dropzone">
                        <p>Drag & Drop Images Here or Enter Image URL:</p>
                        <input type="text" id="icon" name="icon" placeholder="Thumbnail URL">
                    </div>
                    <textarea id="note" name="note" placeholder="Note"></textarea>
                    <select id="category" name="category">
                        <option value="">Select Category (optional)</option>
                    </select>
                    <div class="form-group">
                        <label for="file">Upload File:</label>
                        <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx,.txt,image/*">
                    </div>
                    <button type="submit" class="btn">Add Module</button>
                </form>
            </div>
        </div>
        <div class="right-container">
            <div id="calendar">
                <div id="calendarWidget" class="calendar-widget">
                    <!-- Calendar will be rendered here by JavaScript -->
                </div>
            </div>
            <div id="deletedFilesSection" style="display: none;">
                <h2>Deleted Files</h2>
                <div id="deletedFilesList"></div>
            </div>
            <button id="toggleDeletedFilesButton" class="btn">Toggle Deleted Files</button>
        </div>
    </div>
    <div class="savedFiles-container" id="savedFiles"></div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Edit Note</h2>
        <textarea id="editNoteTextarea" rows="6" cols="50"></textarea>
        <button id="saveEditButton" class="btn">Save</button>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush
@endsection 