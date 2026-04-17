use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

public function exportExcel()
{
return Excel::download(new UsersExport, 'data_user.xlsx');
}