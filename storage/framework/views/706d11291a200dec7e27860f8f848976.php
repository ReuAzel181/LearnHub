

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Notes Tool Card -->
    <a href="<?php echo e(route('notes')); ?>" class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <i class="fas fa-sticky-note text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Notes</h3>
                <p class="text-sm text-gray-500">Create and organize your study notes</p>
            </div>
        </div>
    </a>

    <!-- Draw Tool Card -->
    <a href="<?php echo e(route('draw')); ?>" class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                <i class="fas fa-paint-brush text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Draw</h3>
                <p class="text-sm text-gray-500">Sketch and illustrate your ideas</p>
            </div>
        </div>
    </a>

    <!-- Calculator Tool Card -->
    <a href="<?php echo e(route('calculator')); ?>" class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-green-100 text-green-600">
                <i class="fas fa-calculator text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Calculator</h3>
                <p class="text-sm text-gray-500">Perform quick calculations</p>
            </div>
        </div>
    </a>

    <!-- Dictionary Tool Card -->
    <a href="<?php echo e(route('dictionary')); ?>" class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-red-100 text-red-600">
                <i class="fas fa-book text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Dictionary</h3>
                <p class="text-sm text-gray-500">Look up word definitions</p>
            </div>
        </div>
    </a>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/dashboard.blade.php ENDPATH**/ ?>