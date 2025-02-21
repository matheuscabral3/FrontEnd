<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeriesFormRequest;
use App\Models\Serie;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeriesController extends Controller
{
    // MÉTODO EQUIVALENTE AO "SELECT" NA BASE...
    public function index(Request $request)
    {
        // ====================
        // $series = ['Punisher', 'Lost', 'Grey\'s Anatomy'];
        // $series = DB::select('SELECT nome FROM series;');

        // Exibir DEBUG -> var_dump($series);
        // dump and die (executa um var_dump, encerra a conexão e não exibe a View - Utilizado para Debug).
        // Exibir DEBUG -> dd($series);
        // ====================


        // $series = Serie::all();
        // $series = Serie::where('nome', 'Lost')->orderBy('nome')->get();        
        // $series = Serie::where('nome', 'Lost')->orderBy('nome')->take(10)->get();
        $series = Serie::query()->orderBy('nome')->get();


        // ====================
        // Modelo para gravar um dado na session.
        // session(['mensagem.sucesso' => 'Série Removida com Sucesso.']);

        // 1º Modelo para obter os dados da session.
        // $mensagemSucesso = $request->session()->get('mensagem.sucesso');

        // 2º Modelo para obter os dados da session.
        $mensagemSucesso = session('mensagem.sucesso');

        // Ao utilizar Flash -> $request->session()->flash()
        // Será "esquecido" automaticamente, sem a necessidade do Forget.
        // $request->session()->forget('mensagem.sucesso'); 
        // ====================


        // ====================
        // 1º Modelo para retornar uma View com informações - Documentação Laravel...
        // return view('listar-series', ['series' => $series]);

        // 2º Modelo para retornar uma View com informações - Documentação Laravel...
        // return view('listar-series', compact('series'));

        // 3º Modelo para retornar uma View com informações - Documentação Laravel...
        return view('series.index')
            ->with('series', $series)
            ->with('mensagemSucesso', $mensagemSucesso);
        // ====================
    }


    public function create()
    {
        // Passo a passo
        // 1º Criar View (resources/views/series/create.blade.php);

        // ========== 2º Criar a rota (routes/web.php) ==========
        // Route::get('/series/criar', [SeriesController::class, 'create']);

        // 3º Retornar a View desejada.
        return view("series.create");
    }


    // MÉTODO EQUIVALENTE AO "INSERT" NA BASE...
    public function store(SeriesFormRequest $request)
    {
        // 1º Modelo - Manual
        // $nomeSerie = $request->input('nome');
        // DB::insert('INSERT INTO series (nome) VALUES (?);', [$nomeSerie]);
        // ====================

        // 2º Modelo - Eloquent
        // $nomeSerie = $request->input('nome');
        // $nomeSerie = $request->nome;
        // $serie = new Serie();
        // $serie->nome = $nomeSerie;
        // $serie->save();
        // ====================

        // 2º Modelo - Eloquent -> Utiliza todos os parâmetros da requisição ao mesmo tempo.
        // Disponível por causa da Model -> '$fillable'
        // dd($request->all());
        // Serie::create($request->only(['nome']));
        // Serie::create($request->except(['_token']));


        // Realizar a validação, antes da Inclusão de uma nova série.
        // $request->validate([
        //     'nome' => ['required', 'min:3']
        // ]);


        // Modelo para gravar um dado na session.
        $serie = Serie::create($request->all());
        // $request->session()->flash("mensagem.sucesso", "Série '$serie->nome' Adicionada com Sucesso.");
        // ====================

        // return redirect('/series');
        // return redirect(route('series.index'));
        // return redirect()->route(route('series.index'));
        return to_route("series.index")->with("mensagem.sucesso", "Série '$serie->nome' Adicionada com Sucesso.");


    }


    // MÉTODO EQUIVALENTE AO "DELETE" NA BASE...
    // public function destroy(Request $request)
    // public function destroy(Serie $series, Request $request)
    public function destroy(Serie $series)
    {
        // DEBUG - O que está sendo passado como parâmetro.
        // dd($request->route());


        // $serie = Serie::find($request->series);    // SELECT FROM series WHERE id = $request->series
        //Serie::destroy($request->series);           // DELETE FROM series WHERE id = $request->series
        $series->delete();


        // Utilizado para guardar na sessão a mensagem.
        // $request->session()->put('mensagem.sucesso', 'Série Removida com sucesso!');

        // Utilizado para guardar apenas 1 request. Será "esquecido" automaticamente.
        // $request->session()->flash('mensagem.sucesso', 'Série Removida com sucesso!');
        // $request->session()->flash('mensagem.sucesso', "Série {$series->nome} Removida com sucesso!");


        return to_route('series.index')
            ->with('mensagem.sucesso', "Série {$series->nome} Removida com sucesso!");
    }


    public function edit(Serie $series)
    {
        return view("series.edit")->with("serie", $series);
    }


    // MÉTODO EQUIVALENTE AO "UPDATE" NA BASE...
    public function update(Serie $series, SeriesFormRequest $request)
    {
        // $series->nome = $request->nome;
        $series->fill($request->all());
        $series->save();

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' atualizada com Sucesso.");
    }

}
