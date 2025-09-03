<?php

namespace App\Imports;

use App\Models\BudgetItem;
use App\Models\ProjectBudget;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BudgetImport implements WithMultipleSheets, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $projectBudget;
    protected $importedItems = [];
    protected $errors = [];

    public function __construct(ProjectBudget $projectBudget)
    {
        $this->projectBudget = $projectBudget;
    }

    public function sheets(): array
    {
        return [
            'Production Items' => new ProductionItemsImport($this->projectBudget),
            'Materials for Hire' => new MaterialsHireImport($this->projectBudget),
            'Labour Items' => new LabourItemsImport($this->projectBudget),
            'Other Items' => new OtherItemsImport($this->projectBudget),
        ];
    }

    public function getImportedItems(): array
    {
        return $this->importedItems;
    }

    public function getImportErrors(): array
    {
        return $this->errors;
    }
}

class ProductionItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    protected $projectBudget;

    public function __construct(ProjectBudget $projectBudget)
    {
        $this->projectBudget = $projectBudget;
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['item_name']) || empty($row['particular'])) {
            return null;
        }

        $budgetedCost = ($row['quantity'] ?? 0) * ($row['unit_price'] ?? 0);

        return new BudgetItem([
            'project_budget_id' => $this->projectBudget->id,
            'category' => 'Materials - Production',
            'item_name' => $row['item_name'],
            'particular' => $row['particular'],
            'unit' => $row['unit'] ?? '',
            'quantity' => $row['quantity'] ?? 0,
            'unit_price' => $row['unit_price'] ?? 0,
            'budgeted_cost' => $budgetedCost,
            'comment' => $row['comment'] ?? '',
            'template_id' => $row['template_id'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'item_name' => 'required|string|max:255',
            'particular' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:500',
            'template_id' => 'nullable|string|max:100',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'item_name.required' => 'Item Name is required for production items',
            'particular.required' => 'Particular is required for production items',
            'quantity.required' => 'Quantity is required and must be greater than 0',
            'unit_price.required' => 'Unit Price is required',
        ];
    }
}

class MaterialsHireImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    protected $projectBudget;

    public function __construct(ProjectBudget $projectBudget)
    {
        $this->projectBudget = $projectBudget;
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['item_name']) || empty($row['particular'])) {
            return null;
        }

        $budgetedCost = ($row['quantity'] ?? 0) * ($row['unit_price'] ?? 0);

        return new BudgetItem([
            'project_budget_id' => $this->projectBudget->id,
            'category' => 'Items for Hire',
            'item_name' => $row['item_name'],
            'particular' => $row['particular'],
            'unit' => $row['unit'] ?? '',
            'quantity' => $row['quantity'] ?? 0,
            'unit_price' => $row['unit_price'] ?? 0,
            'budgeted_cost' => $budgetedCost,
            'comment' => $row['comment'] ?? '',
        ]);
    }

    public function rules(): array
    {
        return [
            'item_name' => 'required|string|max:255',
            'particular' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ];
    }
}

class LabourItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    protected $projectBudget;

    public function __construct(ProjectBudget $projectBudget)
    {
        $this->projectBudget = $projectBudget;
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['category']) || empty($row['particular'])) {
            return null;
        }

        $budgetedCost = ($row['quantity'] ?? 0) * ($row['unit_price'] ?? 0);

        return new BudgetItem([
            'project_budget_id' => $this->projectBudget->id,
            'category' => $row['category'],
            'item_name' => null, // Labour items don't have item_name
            'particular' => $row['particular'],
            'unit' => $row['unit'] ?? '',
            'quantity' => $row['quantity'] ?? 0,
            'unit_price' => $row['unit_price'] ?? 0,
            'budgeted_cost' => $budgetedCost,
            'comment' => $row['comment'] ?? '',
        ]);
    }

    public function rules(): array
    {
        return [
            'category' => 'required|string|max:255',
            'particular' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ];
    }
}

class OtherItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    protected $projectBudget;

    public function __construct(ProjectBudget $projectBudget)
    {
        $this->projectBudget = $projectBudget;
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['category']) || empty($row['particular'])) {
            return null;
        }

        $budgetedCost = ($row['quantity'] ?? 0) * ($row['unit_price'] ?? 0);

        return new BudgetItem([
            'project_budget_id' => $this->projectBudget->id,
            'category' => $row['category'],
            'item_name' => $row['item_name'] ?? null,
            'particular' => $row['particular'],
            'unit' => $row['unit'] ?? '',
            'quantity' => $row['quantity'] ?? 0,
            'unit_price' => $row['unit_price'] ?? 0,
            'budgeted_cost' => $budgetedCost,
            'comment' => $row['comment'] ?? '',
        ]);
    }

    public function rules(): array
    {
        return [
            'category' => 'required|string|max:255',
            'particular' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ];
    }
}