import 'dart:async';
import 'dart:io';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:cinema_mobile/config/api_config.dart';
import 'seances_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({Key? key}) : super(key: key);

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;
  String? _errorMessage;

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final url = '${ApiConfig.baseUrl}${ApiConfig.login}';
      print('Tentative de connexion à: $url');
      
      // Print request details for debugging
      print('Request URL: $url');
      print('Request Headers: ${{
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      }}');
      print('Request Body: ${jsonEncode({
        'email': _emailController.text,
        'password': _passwordController.text,
      })}');
      
      final response = await http.post(
        Uri.parse(url),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'email': _emailController.text,
          'password': _passwordController.text,
        }),
      ).timeout(
        const Duration(seconds: 10),
        onTimeout: () {
          throw TimeoutException('La connexion a expiré. Vérifiez votre connexion internet.');
        },
      );

      print('Réponse reçue - Status: ${response.statusCode}');
      print('Headers de la réponse: ${response.headers}');
      print('Corps de la réponse: ${response.body}');
      
      final dynamic responseData = json.decode(response.body);
      print('Données décodées: $responseData');
      
      if (response.statusCode == 200 && responseData is Map && responseData['success'] == true) {
        final userData = responseData['user'];
        print('Données utilisateur: $userData');
        
        if (userData is Map && userData['id'] != null) {
          final userId = int.tryParse(userData['id'].toString());
          if (userId != null) {
            if (!mounted) return;
            Navigator.of(context).pushReplacementNamed(
              '/home',
              arguments: {'utilisateurId': userId},
            );
            return;
          }
        }
      }
      
      // Si on arrive ici, il y a eu une erreur
      final errorMessage = responseData is Map 
          ? (responseData['message'] ?? 'Email ou mot de passe incorrect')
          : 'Réponse inattendue du serveur';
          
      setState(() {
        _errorMessage = errorMessage.toString();
      });
    } catch (e, stackTrace) {
      print('Erreur détaillée: $e');
      print('Stack trace: $stackTrace');
      
      String errorMessage = 'Erreur de connexion';
      if (e is TimeoutException) {
        errorMessage = 'La connexion a expiré. Vérifiez votre connexion internet.';
      } else if (e is SocketException) {
        errorMessage = 'Impossible de se connecter au serveur. Vérifiez votre connexion internet.';
      } else if (e is http.ClientException) {
        errorMessage = 'Erreur de communication avec le serveur: ${e.message}';
      } else {
        errorMessage = 'Erreur inattendue: $e';
      }
      
      setState(() {
        _errorMessage = errorMessage;
      });
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24.0),
          child: Form(
            key: _formKey,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const Text(
                  'Cinéma App',
                  style: TextStyle(
                    fontSize: 32,
                    fontWeight: FontWeight.bold,
                    color: Colors.black87,
                  ),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 48),
                TextFormField(
                  controller: _emailController,
                  decoration: const InputDecoration(
                    labelText: 'Email',
                    prefixIcon: Icon(Icons.email),
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.emailAddress,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Veuillez entrer votre email';
                    }
                    if (!value.contains('@')) {
                      return 'Veuillez entrer un email valide';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _passwordController,
                  decoration: const InputDecoration(
                    labelText: 'Mot de passe',
                    prefixIcon: Icon(Icons.lock),
                    border: OutlineInputBorder(),
                  ),
                  obscureText: true,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Veuillez entrer votre mot de passe';
                    }
                    return null;
                  },
                ),
                if (_errorMessage != null) ...{
                  const SizedBox(height: 16),
                  Text(
                    _errorMessage!,
                    style: const TextStyle(color: Colors.red),
                    textAlign: TextAlign.center,
                  ),
                },
                const SizedBox(height: 24),
                ElevatedButton(
                  onPressed: _isLoading ? null : _login,
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  child: _isLoading
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                          ),
                        )
                      : const Text('Se connecter'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
